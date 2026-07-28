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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('purchase_order_no')->nullable();
            $table->string('purchase_order_date')->nullable();
            $table->string('delivery_date')->nullable();
            $table->string('status')->nullable();
            $table->string('reference_no')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('sale_order_id')->nullable();
            $table->decimal('total_qty', 18, 3)->nullable();
            $table->boolean('is_complete')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->integer('approvedby_id')->nullable();
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
        Schema::dropIfExists('purchase_orders');
    }
};
