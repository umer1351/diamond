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
        Schema::create('ratti_kaats', function (Blueprint $table) {
            $table->id();
            $table->string('ratti_kaat_no')->nullable();
            $table->date('purchase_date')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('purchase_account')->nullable();
            $table->decimal('paid',18,3)->nullable();
            $table->integer('paid_account')->nullable();
            $table->decimal('paid_au',18,3)->nullable();
            $table->integer('paid_account_au')->nullable();
            $table->decimal('paid_dollar',18,3)->nullable();
            $table->integer('paid_account_dollar')->nullable();
            $table->string('reference')->nullable();
            $table->text('pictures')->nullable();
            $table->decimal('tax_amount',18,3)->nullable();
            $table->integer('tax_account')->nullable();
            $table->decimal('sub_total',18,3)->nullable();
            $table->decimal('total',18,3)->nullable();
            $table->decimal('total_au',18,3)->nullable();
            $table->decimal('total_dollar',18,3)->nullable();
            $table->integer('jv_id')->nullable();
            $table->integer('paid_jv_id')->nullable();
            $table->integer('paid_au_jv_id')->nullable();
            $table->integer('paid_dollar_jv_id')->nullable();
            $table->boolean('is_active')->default(1)->comment('1 for active, 0 for inactive');
            $table->boolean('is_posted')->default(0);
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
        Schema::dropIfExists('ratti_kaats');
    }
};
