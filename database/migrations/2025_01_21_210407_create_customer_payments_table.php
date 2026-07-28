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
        Schema::create('customer_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('reference')->nullable();
            $table->integer('currency')->default(0)->comment('0 for PKR, 1 for AU and 2 for Dollar');
            $table->decimal('tax',18,3)->nullable();
            $table->decimal('tax_amount',18,3)->nullable();
            $table->integer('tax_account_id')->nullable();
            $table->decimal('sub_total',18,3)->nullable();
            $table->decimal('total',18,3)->nullable();
            $table->integer('jv_id')->nullable();
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
        Schema::dropIfExists('customer_payments');
    }
};
