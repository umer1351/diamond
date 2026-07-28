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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable()->comment('0 for purchase, 1 for other sale');
            $table->date('date')->nullable();
            $table->integer('other_product_id')->nullable();
            $table->decimal('qty',18,3)->nullable();
            $table->decimal('unit_price',18,3)->nullable();
            $table->integer('other_purchase_id')->nullable();
            $table->integer('other_sale_id')->nullable();
            $table->integer('stock_taking_id')->nullable();
            $table->integer('stock_taking_link_id')->nullable();
            $table->integer('warehouse_id')->nullable();
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
        Schema::dropIfExists('transactions');
    }
};
