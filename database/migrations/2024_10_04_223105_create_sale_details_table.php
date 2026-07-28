<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_details', function (Blueprint $table) {
            $table->id();
            $table->integer('sale_id')->nullable();
            $table->integer('finish_product_id')->nullable();
            $table->integer('ratti_kaat_id')->nullable();
            $table->integer('ratti_kaat_detail_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('gold_carat',18,3)->default(0);
            $table->decimal('scale_weight',18,3)->default(0);
            $table->decimal('bead_weight',18,3)->default(0);
            $table->decimal('stones_weight',18,3)->default(0);
            $table->decimal('diamond_weight',18,3)->default(0);
            $table->decimal('net_weight',18,3)->default(0);
            $table->decimal('waste',18,3)->default(0);
            $table->decimal('gross_weight',18,3)->default(0);
            $table->decimal('making',18,3)->default(0);
            $table->decimal('bead_price',18,3)->default(0);
            $table->decimal('stones_price',18,3)->default(0);
            $table->decimal('diamond_price',18,3)->default(0);
            $table->decimal('total_bead_price',18,3)->default(0);
            $table->decimal('total_stones_price',18,3)->default(0);
            $table->decimal('total_diamond_price',18,3)->default(0);
            $table->decimal('other_amount',18,3)->default(0);
            $table->decimal('gold_rate',18,3)->default(0);
            $table->decimal('total_gold_price',18,3)->default(0);
            $table->decimal('total_amount',18,3)->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->integer('createdby_id')->nullable();
            $table->integer('deletedby_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_details');
    }
};
