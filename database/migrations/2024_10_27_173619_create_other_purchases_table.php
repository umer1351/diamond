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
        Schema::create('other_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('other_purchase_no')->nullable();
            $table->string('other_purchase_date')->nullable();
            $table->string('bill_no')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->text('reference')->nullable();
            $table->decimal('total_qty',18,3)->nullable();
            $table->decimal('tax',18,3)->nullable();
            $table->decimal('tax_amount',18,3)->nullable();
            $table->decimal('sub_total',18,3)->nullable();
            $table->decimal('total',18,3)->nullable();
            $table->decimal('paid',18,3)->nullable();
            $table->integer('purchase_account_id')->nullable();
            $table->integer('paid_account_id')->nullable();
            $table->integer('supplier_payment_id')->nullable();
            $table->integer('jv_id')->nullable();
            $table->integer('paid_jv_id')->nullable();
            $table->boolean('posted')->default(0);
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
        Schema::dropIfExists('other_purchases');
    }
};
