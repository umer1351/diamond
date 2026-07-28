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
        Schema::create('gold_impurity_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('gold_impurity_purchase_no')->nullable();
            $table->integer('customer_id')->nullable();
            $table->decimal('total_qty',18,3)->default(0);
            $table->decimal('total_weight',18,3)->default(0);
            $table->decimal('total',18,2)->default(0);
            $table->decimal('cash_payment',18,2)->default(0);
            $table->decimal('bank_payment',18,2)->default(0);
            $table->decimal('total_payment',18,2)->default(0);
            $table->decimal('balance',18,2)->default(0);
            $table->integer('jv_id')->nullable();
            $table->integer('cash_jv_id')->nullable();
            $table->integer('bank_jv_id')->nullable();
            $table->boolean('is_posted')->default(0);
            $table->boolean('is_mix_stock')->default(0);
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
        Schema::dropIfExists('gold_impurity_purchases');
    }
};
