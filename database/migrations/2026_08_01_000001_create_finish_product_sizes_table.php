<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Per-size stock for variable products (Bangles / Rings). A single
     * finish_products row (one tag_no) can be sold in several sizes, each
     * with its own available quantity.
     */
    public function up(): void
    {
        Schema::create('finish_product_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finish_product_id')->index();
            $table->string('tag_no', 100)->nullable()->index();
            $table->string('size', 50);
            $table->integer('quantity')->default(0);
            $table->timestamps();

            $table->unique(['finish_product_id', 'size']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finish_product_sizes');
    }
};
