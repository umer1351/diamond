<?php

namespace App\Console\Commands;

use App\Models\FinishProduct;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorPNG;

class ImportCatalog extends Command
{
    /**
     * Import the storefront catalog from the Azure Luxury products CSV.
     *
     * Handles the real-world CSV quirks the generic web importer cannot:
     *  - prices formatted like "QAR 85.00" (and thousands separators)
     *  - Sizes / Quantity columns
     *  - repeated tag_no rows (size variants) which are merged into one product
     */
    protected $signature = 'catalog:import {file : Absolute path to the CSV file}
                            {--user=1 : User id recorded as creator/updater}';

    protected $description = 'Import jewellery products from the catalog CSV into finish_products';

    public function handle(): int
    {
        $file = (string) $this->argument('file');

        if (! is_file($file)) {
            $this->error("CSV file not found: {$file}");
            return self::FAILURE;
        }

        $userId = (int) $this->option('user');

        $warehouse = Warehouse::where('is_deleted', 0)->orderBy('id')->first();
        if (! $warehouse) {
            $this->error('No warehouse found. Create a warehouse before importing.');
            return self::FAILURE;
        }

        $handle = fopen($file, 'rb');
        if (! $handle) {
            $this->error('Unable to open the CSV file.');
            return self::FAILURE;
        }

        $header = fgetcsv($handle);
        if (! is_array($header)) {
            fclose($handle);
            $this->error('CSV file is empty.');
            return self::FAILURE;
        }

        $columns = array_map(function ($column) {
            return Str::of((string) $column)->lower()->trim()->replace([' ', '-'], '_')->toString();
        }, $header);

        // Pass 1 - read and group rows by tag_no (merging size variants).
        $groups = [];
        while (($row = fgetcsv($handle)) !== false) {
            if ($this->isBlankRow($row)) {
                continue;
            }

            $data = [];
            foreach ($columns as $i => $col) {
                if ($col === '') {
                    continue;
                }
                $data[$col] = $this->toUtf8($row[$i] ?? null);
            }

            $tagNo = trim((string) ($data['tag_no'] ?? ''));
            if ($tagNo === '') {
                continue;
            }

            $size = trim((string) ($data['sizes'] ?? ''));
            $qty = (int) $this->number($data['quantity'] ?? null);

            if (! isset($groups[$tagNo])) {
                $groups[$tagNo] = [
                    'tag_no' => $tagNo,
                    'category_name' => trim((string) ($data['category_name'] ?? '')),
                    'category_prefix' => strtoupper(trim((string) ($data['category_prefix'] ?? ''))),
                    'product_name' => trim((string) ($data['product_name'] ?? '')),
                    'image_filename' => trim((string) ($data['image_filename'] ?? '')),
                    'short_description' => trim((string) ($data['short_description'] ?? '')),
                    'total_amount' => $this->money($data['total_amount'] ?? null),
                    'sizes' => [],
                    'stock_quantity' => 0,
                ];
            }

            if ($size !== '' && strtoupper($size) !== 'N/A') {
                if (! in_array($size, $groups[$tagNo]['sizes'], true)) {
                    $groups[$tagNo]['sizes'][] = $size;
                }
            }

            $groups[$tagNo]['stock_quantity'] += max(0, $qty);
        }

        fclose($handle);

        // Pass 2 - persist.
        File::ensureDirectoryExists(public_path('barcodes'));
        $generator = new BarcodeGeneratorPNG();

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($groups as $tagNo => $g) {
            if ($g['category_name'] === '' && $g['category_prefix'] === '') {
                $this->warn("Skipped {$tagNo}: missing category.");
                $skipped++;
                continue;
            }

            $category = $this->resolveCategory($g['category_name'], $g['category_prefix'], $userId);

            $productName = $g['product_name'] !== '' ? $g['product_name'] : $tagNo;

            $barcodePath = 'barcodes/' . $this->safeFileName($tagNo) . '.png';
            file_put_contents(
                public_path($barcodePath),
                $generator->getBarcode($tagNo, $generator::TYPE_CODE_39)
            );

            $existing = FinishProduct::where('tag_no', $tagNo)->first();

            $payload = [
                'tag_no' => $tagNo,
                'product_id' => $category->id,
                'product_name' => $productName,
                'warehouse_id' => $warehouse->id,
                'picture' => $this->resolvePicture($g['image_filename']),
                'barcode' => $barcodePath,
                'short_description' => $g['short_description'],
                'long_description' => $g['short_description'],
                'tags' => $g['category_name'],
                'sizes' => $g['sizes'] === [] ? null : implode(', ', $g['sizes']),
                'stock_quantity' => $g['stock_quantity'],
                'total_amount' => $g['total_amount'],
                'is_active' => 1,
                'is_deleted' => 0,
                'updatedby_id' => $userId,
            ];

            if ($existing) {
                $existing->update($payload);
                $updated++;
            } else {
                $payload['createdby_id'] = $userId;
                FinishProduct::create($payload);
                $created++;
            }
        }

        $this->info("Catalog import complete. Created: {$created}, Updated: {$updated}, Skipped: {$skipped}, Products (rows grouped): " . count($groups) . '.');

        return self::SUCCESS;
    }

    private function resolveCategory(string $name, string $prefix, int $userId): Product
    {
        $query = Product::where('is_deleted', 0);
        $query->where(function ($q) use ($name, $prefix) {
            if ($name !== '') {
                $q->where('name', $name);
            }
            if ($prefix !== '') {
                $name !== '' ? $q->orWhere('prefix', $prefix) : $q->where('prefix', $prefix);
            }
        });

        $category = $query->first();

        if ($category) {
            return $category;
        }

        return Product::create([
            'name' => $name !== '' ? $name : $prefix,
            'prefix' => $prefix !== '' ? $prefix : Str::upper(Str::substr($name, 0, 3)),
            'is_active' => 1,
            'is_deleted' => 0,
            'createdby_id' => $userId,
            'updatedby_id' => $userId,
        ]);
    }

    /**
     * Store the picture path. If a real file exists in public/pictures with a
     * common extension, use it; otherwise store the base path (the storefront
     * gracefully falls back to gallery images when the file is missing).
     */
    private function resolvePicture(string $imageName): ?string
    {
        $imageName = trim($imageName);
        if ($imageName === '') {
            return null;
        }

        $imageName = ltrim(str_replace('\\', '/', $imageName), '/');

        if (Str::contains($imageName, '/')) {
            return $imageName;
        }

        if (is_file(public_path('pictures/' . $imageName))) {
            return 'pictures/' . $imageName;
        }

        foreach (['jpg', 'jpeg', 'png', 'webp', 'PNG', 'JPG', 'JPEG'] as $ext) {
            if (is_file(public_path('pictures/' . $imageName . '.' . $ext))) {
                return 'pictures/' . $imageName . '.' . $ext;
            }
        }

        return 'pictures/' . $imageName;
    }

    private function safeFileName(string $value): string
    {
        return preg_replace('/[^A-Za-z0-9._-]/', '_', $value);
    }

    /**
     * The source CSV is Windows-1252 encoded (e.g. "pavé"). Convert any
     * non-UTF-8 bytes so MySQL (utf8/utf8mb4) accepts them.
     */
    private function toUtf8($value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = (string) $value;

        if ($value === '' || mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }

    private function isBlankRow(array $row): bool
    {
        foreach ($row as $value) {
            if (trim((string) $value) !== '') {
                return false;
            }
        }
        return true;
    }

    private function number($value): float
    {
        $value = trim((string) $value);
        return $value === '' ? 0.0 : (float) $value;
    }

    /**
     * Parse a money value that may contain a currency label / thousands
     * separators, e.g. "QAR 1,250.00" -> 1250.00.
     */
    private function money($value): float
    {
        $value = str_replace(',', '', trim((string) $value));
        $value = preg_replace('/[^0-9.]/', '', $value);

        return $value === '' ? 0.0 : (float) $value;
    }
}
