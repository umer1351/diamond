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
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->integer('journal_entry_id')->nullable();
            $table->longText('explanation')->nullable();
            $table->string('bill_no')->nullable();
            $table->string('check_no')->nullable();
            $table->date('check_date')->nullable();
            $table->decimal('credit', 10, 2)->default(0);
            $table->decimal('debit', 10, 2)->default(0);
            $table->integer('currency')->default(0)->comment('0 for PKR, 1 for AU and 2 for Dollar');
            $table->string('doc_date')->nullable();
            $table->integer('account_id')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('amount_in_words')->nullable();
            $table->string('account_code')->nullable();
            $table->integer('createdby_id')->nullable();
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
        Schema::dropIfExists('journal_entry_details');
    }
};
