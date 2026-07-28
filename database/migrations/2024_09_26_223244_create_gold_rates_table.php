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
        Schema::create('gold_rates', function (Blueprint $table) {
            $table->id();
            $table->integer('carat')->default(24);
            $table->decimal('gold',18,3)->default(100);
            $table->decimal('impurity',18,3)->default(0);
            $table->integer('ratti')->default(96);
            $table->integer('ratti_impurity')->default(0);
            $table->decimal('rate_tola',18,3)->default(0);
            $table->decimal('rate_gram',18,3)->default(0);
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
        Schema::dropIfExists('gold_rates');
    }
};
