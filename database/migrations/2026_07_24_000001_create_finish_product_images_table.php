<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finish_product_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finish_product_id')->index();
            $table->string('path');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Backfill the existing single `picture` as each product's first gallery image.
        if (Schema::hasTable('finish_products') && Schema::hasColumn('finish_products', 'picture')) {
            DB::table('finish_products')
                ->whereNotNull('picture')
                ->where('picture', '!=', '')
                ->orderBy('id')
                ->select('id', 'picture', 'created_at', 'updated_at')
                ->chunk(200, function ($rows) {
                    $now = now();
                    $insert = [];
                    foreach ($rows as $row) {
                        $insert[] = [
                            'finish_product_id' => $row->id,
                            'path' => $row->picture,
                            'sort_order' => 0,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                    if ($insert !== []) {
                        DB::table('finish_product_images')->insert($insert);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('finish_product_images');
    }
};
