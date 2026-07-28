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
        Schema::create('ratti_kaat_details', function (Blueprint $table) {
            $table->id();
            $table->integer('ratti_kaat_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('description')->nullable();
            $table->decimal('scale_weight',18,3)->default(0.00);
            $table->decimal('bead_weight',18,3)->default(0.00);
            $table->decimal('stones_weight',18,3)->default(0.00);
            $table->decimal('diamond_carat',18,3)->default(0.00);
            $table->decimal('net_weight',18,3)->default(0.00);
            $table->decimal('supplier_kaat',18,3)->default(0.00);
            $table->decimal('kaat',18,3)->default(0.00);
            $table->integer('approved_by')->nullable();
            $table->decimal('pure_payable',18,3)->default(0.00);
            $table->decimal('other_charge',18,3)->default(0.00);
            $table->decimal('total_bead_amount',18,3)->default(0.00);
            $table->decimal('total_stones_amount',18,3)->default(0.00);
            $table->decimal('total_diamond_amount',18,3)->default(0.00);
            $table->decimal('total_amount',18,3)->default(0.00);
            $table->decimal('total_dollar',18,3)->default(0.00);
            $table->boolean('is_finish_product')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->integer('createdby_id')->nullable();
            $table->integer('updatedby_id')->nullable();
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
        Schema::dropIfExists('ratti_kaat_details');
    }
};
