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
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->string('sale_order_no')->nullable();
            $table->string('sale_order_date')->nullable();
            $table->integer('customer_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->decimal('total_qty',18,3)->nullable();
            $table->decimal('gold_rate',18,3)->nullable();
            $table->integer('gold_rate_type_id')->nullable();
            $table->boolean('is_purchased')->default(0);
            $table->boolean('is_saled')->default(0);
            $table->boolean('is_complete')->default(0);
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
        Schema::dropIfExists('sale_orders');
    }
};
