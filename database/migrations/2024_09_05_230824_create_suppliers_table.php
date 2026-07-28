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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('contact')->nullable();
            $table->string('cnic')->nullable();
            $table->string('company')->nullable();
            $table->integer('type')->default(0)->comment('0 for supplier, 1 for karigar and 2 for both');
            $table->integer('account_id')->nullable();
            $table->integer('account_au_id')->nullable();
            $table->integer('account_dollar_id')->nullable();
            $table->decimal('gold_waste')->default(0.00)->comment('waste/tola');
            $table->decimal('stone_waste')->default(0.00)->comment('Stone Studding Waste');
            $table->decimal('kaat')->default(0.00)->comment('kaat/tola');
            $table->string('bank_name')->nullable();
            $table->string('account_title')->nullable();
            $table->string('account_no')->nullable();
            $table->boolean('is_active')->default(1);
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
        Schema::dropIfExists('suppliers');
    }
};
