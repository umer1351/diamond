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
        Schema::create('company_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();
            $table->integer('purchase_account_id')->nullable();
            $table->integer('sale_account_id')->nullable();
            $table->integer('cash_account_id')->nullable();
            $table->integer('revenue_account_id')->nullable();
            $table->integer('bank_account_id')->nullable();
            $table->integer('card_account_id')->nullable();
            $table->integer('advance_account_id')->nullable();
            $table->integer('gold_impurity_account_id')->nullable();
            $table->integer('createdby_id')->nullable();
            $table->integer('updatedby_id')->nullable();
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
        Schema::dropIfExists('company_settings');
    }
};
