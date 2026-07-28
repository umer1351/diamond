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
        Schema::create('job_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('job_purchase_no')->nullable();
            $table->date('job_purchase_date')->nullable();
            $table->integer('purchase_order_id')->nullable();
            $table->integer('sale_order_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('purchase_account_id')->nullable();
            $table->string('reference')->nullable();
            $table->decimal('total_recieved_au',18,3)->nullable();
            $table->decimal('total',18,3)->nullable();
            $table->decimal('total_au',18,3)->nullable();
            $table->decimal('total_dollar',18,3)->nullable();
            $table->integer('jv_id')->nullable();
            $table->integer('jv_au_id')->nullable();
            $table->integer('jv_dollar_id')->nullable();
            $table->integer('jv_recieved_id')->nullable();
            $table->integer('supplier_au_payment_id')->nullable();
            $table->boolean('is_active')->default(1)->comment('1 for active, 0 for inactive');
            $table->boolean('is_posted')->default(0);
            $table->boolean('is_saled')->default(0);
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
        Schema::dropIfExists('job_purchases');
    }
};
