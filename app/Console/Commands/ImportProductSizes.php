<?php

namespace App\Console\Commands;

use App\Models\FinishProduct;
use App\Models\FinishProductSize;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportProductSizes extends Command
{
    /**
     * Usage: php artisan sizes:import {path?}
     * Defaults to database/data/product-sizes.csv.
     *
     * CSV columns (header row required): tag_no, category_name, category_prefix,
     * product_name, image_filename, Sizes, Quantity, ...
     *
     * Each (tag_no, Sizes) row carries a per-size Quantity. Rows whose size is
     * "N/A" / "Free" / blank are treated as a single stock figure with no size
     * variation. finish_products.stock_quantity is refreshed to the sum, and
     * finish_products.sizes is refreshed to the comma list of real sizes.
     */
    protected $signature = 'sizes:import {path? : Path to the CSV file}';

    protected $description = 'Import per-size stock for variable products (Bangles / Rings) from a CSV.';

    public function handle(): int
    {
        $path = $this->argument('path') ?: database_path('data/product-sizes.csv');

        if (! is_file($path)) {
            $this->error("CSV not found: {$path}");
            return self::FAILURE;
        }

        $handle = fopen($path, 'r');
        if ($handle === false) {
            $this->error("Unable to open CSV: {$path}");
            return self::FAILURE;
        }

        $header = fgetcsv($handle);
        if ($header === false) {
            $this->error('CSV is empty.');
            fclose($handle);
            return self::FAILURE;
        }

        // Resolve column positions from the header (case/space insensitive).
        $normalized = array_map(fn ($h) => strtolower(trim((string) $h)), $header);
        $col = fn (string $name) => array_search($name, $normalized, true);

        $tagIdx = $col('tag_no');
        $sizeIdx = $col('sizes');
        $qtyIdx = $col('quantity');

        if ($tagIdx === false || $sizeIdx === false || $qtyIdx === false) {
            $this->error('CSV must contain tag_no, Sizes and Quantity columns.');
            fclose($handle);
            return self::FAILURE;
        }

        // rows[tag][size] = quantity
        $rows = [];
        while (($data = fgetcsv($handle)) !== false) {
            $tag = trim((string) ($data[$tagIdx] ?? ''));
            if ($tag === '') {
                continue;
            }

            $size = trim((string) ($data[$sizeIdx] ?? ''));
            $qty = (int) preg_replace('/[^0-9\-]/', '', (string) ($data[$qtyIdx] ?? '0'));

            $rows[$tag] ??= [];
            $rows[$tag][$size] = ($rows[$tag][$size] ?? 0) + $qty;
        }
        fclose($handle);

        $touchedTags = 0;
        $sizeRows = 0;
        $missing = [];

        foreach ($rows as $tag => $sizes) {
            $product = FinishProduct::where('tag_no', $tag)->where('is_deleted', 0)->first()
                ?? FinishProduct::where('tag_no', $tag)->first();

            if (! $product) {
                $missing[] = $tag;
                continue;
            }

            DB::transaction(function () use ($product, $sizes, &$sizeRows) {
                FinishProductSize::where('finish_product_id', $product->id)->delete();

                $realSizes = [];
                $total = 0;

                foreach ($sizes as $size => $qty) {
                    $total += (int) $qty;

                    $clean = trim((string) $size);
                    $isRealSize = $clean !== '' && ! in_array(strtolower($clean), ['n/a', 'na', 'free', 'free size', 'freesize'], true);

                    if ($isRealSize) {
                        $realSizes[] = $clean;
                        FinishProductSize::create([
                            'finish_product_id' => $product->id,
                            'tag_no' => $product->tag_no,
                            'size' => $clean,
                            'quantity' => (int) $qty,
                        ]);
                        $sizeRows++;
                    }
                }

                $product->stock_quantity = $total;
                $product->sizes = $realSizes === [] ? null : implode(', ', array_values(array_unique($realSizes)));
                $product->save();
            });

            $touchedTags++;
        }

        $this->info("Updated {$touchedTags} products, {$sizeRows} size rows.");
        if ($missing !== []) {
            $this->warn('No matching finish_products for tags: ' . implode(', ', array_slice($missing, 0, 40)) . (count($missing) > 40 ? ' …' : ''));
        }

        return self::SUCCESS;
    }
}
