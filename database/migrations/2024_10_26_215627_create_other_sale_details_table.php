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
        Schema::create('other_sale_details', function (Blueprint $table) {
            $table->id();
            $table->integer('other_sale_id')->nullable();
            $table->integer('other_product_id')->nullable();
            $table->decimal('qty',18,3)->default(0);
            $table->decimal('unit_price',18,3)->default(0);
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
        Schema::dropIfExists('other_sale_details');
    }
};
