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
        Schema::create('other_sales', function (Blueprint $table) {
            $table->id();
            $table->string('other_sale_no')->nullable();
            $table->string('other_sale_date')->nullable();
            $table->integer('customer_id')->nullable();
            $table->string('customer_name')->nullable();
            $table->string('customer_cnic')->nullable();
            $table->string('customer_contact')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_address')->nullable();
            $table->decimal('total_qty',18,3)->nullable();
            $table->decimal('tax',18,3)->nullable();
            $table->decimal('tax_amount',18,3)->nullable();
            $table->decimal('sub_total',18,3)->nullable();
            $table->decimal('total',18,3)->nullable();
            $table->boolean('is_credit')->default(0);
            $table->decimal('cash_amount',18,3)->nullable();
            $table->decimal('bank_transfer_amount',18,3)->nullable();
            $table->decimal('card_amount',18,3)->nullable();
            $table->decimal('advance_amount',18,3)->nullable();
            $table->decimal('total_received',18,3)->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('jv_id')->nullable();
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
        Schema::dropIfExists('other_sales');
    }
};
