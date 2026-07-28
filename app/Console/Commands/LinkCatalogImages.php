<?php

namespace App\Console\Commands;

use App\Models\FinishProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinkCatalogImages extends Command
{
    /**
     * Match extracted product images (named by tag number) to imported
     * finish_products, web-optimise them (resize + compress) into
     * public/pictures, and link them via the `picture` column.
     */
    protected $signature = 'catalog:link-images {dir : Folder containing the product images}
                            {--max=1000 : Max width/height in px for the optimised image (0 = keep original size)}
                            {--quality=82 : JPEG quality 1-100}
                            {--copy : Copy originals as-is instead of optimising}';

    protected $description = 'Link and optimise catalog product images from an image folder';

    public function handle(): int
    {
        $dir = (string) $this->argument('dir');

        if (! is_dir($dir)) {
            $this->error("Image folder not found: {$dir}");
            return self::FAILURE;
        }

        // 1. Index every image in the folder (recursively) by a normalised key.
        $index = [];
        foreach (File::allFiles($dir) as $file) {
            $ext = strtolower($file->getExtension());
            if (! in_array($ext, ['png', 'jpg', 'jpeg', 'webp'], true)) {
                continue;
            }

            $base = $file->getFilenameWithoutExtension();

            // Skip obvious duplicate exports like "KW-RG-016 (1)".
            if (preg_match('/\(\d+\)\s*$/', $base)) {
                continue;
            }

            $key = $this->normalizeKey($base);
            // First writer wins; keeps the primary file over odd variants.
            if (! isset($index[$key])) {
                $index[$key] = $file->getPathname();
            }
        }

        // Manual aliases for known filename/tag mismatches.
        $aliases = [
            $this->normalizeKey('KW-LMTE-010') => $this->normalizeKey('KW-LMTE-0010'),
        ];

        $this->info('Indexed ' . count($index) . ' source images.');

        File::ensureDirectoryExists(public_path('pictures'));

        $products = FinishProduct::where('is_deleted', 0)->get();

        $linked = 0;
        $missing = [];

        foreach ($products as $product) {
            $tag = trim((string) $product->tag_no);
            if ($tag === '') {
                continue;
            }

            $key = $this->normalizeKey($tag);
            $source = $index[$key] ?? null;

            if (! $source && isset($aliases[$key], $index[$aliases[$key]])) {
                $source = $index[$aliases[$key]];
            }

            if (! $source) {
                $missing[] = $tag;
                continue;
            }

            $targetName = $this->safeFileName($tag);
            $useCopy = (bool) $this->option('copy');

            if ($useCopy) {
                $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
                $relative = 'pictures/' . $targetName . '.' . $ext;
                File::copy($source, public_path($relative));
            } else {
                $relative = 'pictures/' . $targetName . '.jpg';
                if (! $this->optimise($source, public_path($relative))) {
                    // Fall back to a straight copy if GD fails on this file.
                    $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
                    $relative = 'pictures/' . $targetName . '.' . $ext;
                    File::copy($source, public_path($relative));
                }
            }

            $product->picture = $relative;
            $product->save();
            $linked++;
        }

        $this->info("Linked images to {$linked} products.");

        if ($missing !== []) {
            $this->warn('No image found for ' . count($missing) . ' products: ' . implode(', ', $missing));
        }

        return self::SUCCESS;
    }

    /**
     * Resize (preserving aspect ratio) and re-encode to JPEG.
     */
    private function optimise(string $source, string $target): bool
    {
        $max = (int) $this->option('max');
        $quality = max(1, min(100, (int) $this->option('quality')));

        $info = @getimagesize($source);
        if ($info === false) {
            return false;
        }

        [$width, $height] = $info;

        $img = match ($info[2]) {
            IMAGETYPE_PNG => @imagecreatefrompng($source),
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source),
            default => false,
        };

        if (! $img) {
            return false;
        }

        $scale = ($max > 0 && max($width, $height) > $max) ? $max / max($width, $height) : 1.0;
        $newW = max(1, (int) round($width * $scale));
        $newH = max(1, (int) round($height * $scale));

        $canvas = imagecreatetruecolor($newW, $newH);
        // Flatten transparency onto white (jewellery shots read well on white).
        $white = imagecolorallocate($canvas, 255, 255, 255);
        imagefilledrectangle($canvas, 0, 0, $newW, $newH, $white);
        imagecopyresampled($canvas, $img, 0, 0, 0, 0, $newW, $newH, $width, $height);

        $ok = imagejpeg($canvas, $target, $quality);

        imagedestroy($img);
        imagedestroy($canvas);

        return $ok;
    }

    private function normalizeKey(string $value): string
    {
        // Uppercase and strip everything except letters/digits so that
        // "KW-DW-005 -2" and "KW-DW-005-2" collapse to the same key.
        return preg_replace('/[^A-Z0-9]/', '', strtoupper($value));
    }

    private function safeFileName(string $value): string
    {
        return preg_replace('/[^A-Za-z0-9._-]/', '_', trim($value));
    }
}
