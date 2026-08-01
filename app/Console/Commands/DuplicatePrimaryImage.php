<?php

namespace App\Console\Commands;

use App\Models\FinishProduct;
use App\Models\FinishProductImage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DuplicatePrimaryImage extends Command
{
    /**
     * Give every finished product a SECOND gallery image by physically copying
     * its current photo to a new file. This lets the storefront show two
     * thumbnails immediately; the client can then replace the 2nd image from
     * the admin panel (Finish Product -> View -> Images).
     *
     * Idempotent: products that already have 2+ images are skipped, so it is
     * safe to run again after the client has uploaded real second photos.
     */
    protected $signature = 'images:duplicate-primary {--force : Re-copy even if a *-copy file already exists}';

    protected $description = 'Duplicate each finished product\'s single photo into a distinct second gallery image.';

    public function handle(): int
    {
        $products = FinishProduct::with('images')
            ->where('is_deleted', 0)
            ->get();

        $created = 0;
        $skipped = 0;
        $missing = 0;

        foreach ($products as $product) {
            $images = $product->images; // already ordered by sort_order, id

            // Already has a real gallery of 2+ images -> leave it alone.
            if ($images->count() >= 2) {
                $skipped++;
                continue;
            }

            // Resolve the primary path: first gallery image, else legacy picture.
            $primaryPath = $images->first()->path ?? trim((string) $product->picture);

            if ($primaryPath === '' || $primaryPath === null) {
                $missing++;
                $this->warn("#{$product->id} {$product->tag_no}: no source image, skipped.");
                continue;
            }

            $normalized = ltrim(str_replace('\\', '/', $primaryPath), '/');
            $absolute = public_path($normalized);

            if (! is_file($absolute)) {
                $missing++;
                $this->warn("#{$product->id} {$product->tag_no}: file not found ({$normalized}), skipped.");
                continue;
            }

            // Make sure the primary is represented as a gallery row (older records
            // may only have the legacy `picture` column).
            if ($images->isEmpty()) {
                FinishProductImage::create([
                    'finish_product_id' => $product->id,
                    'path' => $normalized,
                    'sort_order' => 0,
                ]);
            }

            // Build the copy filename next to the original.
            $dir = dirname($normalized);
            $ext = pathinfo($normalized, PATHINFO_EXTENSION);
            $base = pathinfo($normalized, PATHINFO_FILENAME);
            $copyRelative = trim($dir . '/' . $base . '-copy-' . $product->id . ($ext ? '.' . $ext : ''), '/');
            $copyAbsolute = public_path($copyRelative);

            if (! is_file($copyAbsolute) || $this->option('force')) {
                File::ensureDirectoryExists(dirname($copyAbsolute));
                File::copy($absolute, $copyAbsolute);
            }

            FinishProductImage::create([
                'finish_product_id' => $product->id,
                'path' => $copyRelative,
                'sort_order' => 1,
            ]);

            if (trim((string) $product->picture) === '') {
                $product->picture = $normalized;
                $product->save();
            }

            $created++;
        }

        $this->info("Done. Second image created for {$created} product(s); {$skipped} already had 2+; {$missing} had no usable source.");

        return self::SUCCESS;
    }
}
