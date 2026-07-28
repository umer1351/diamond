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
        Schema::create('gold_impurity_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->integer('gold_impurity_purchase_id')->nullable();
            $table->decimal('scale_weight',18,3)->default(0);
            $table->decimal('bead_weight',18,3)->default(0);
            $table->decimal('stone_weight',18,3)->default(0);
            $table->decimal('net_weight',18,3)->default(0);
            $table->decimal('point',18,3)->default(0);
            $table->decimal('pure_weight',18,3)->default(0);
            $table->decimal('gold_rate',18,3)->default(0);
            $table->decimal('total_amount',18,3)->default(0);
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
        Schema::dropIfExists('gold_impurity_purchase_details');
    }
};
