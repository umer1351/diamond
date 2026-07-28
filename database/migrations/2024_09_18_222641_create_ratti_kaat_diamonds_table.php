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
        Schema::create('ratti_kaat_diamonds', function (Blueprint $table) {
            $table->id();
            $table->integer('ratti_kaat_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->decimal('diamonds',18,3)->default(0.00);
            $table->string('type')->nullable();
            $table->string('color')->nullable();
            $table->string('cut')->nullable();
            $table->string('clarity')->nullable();
            $table->decimal('carat',18,3)->default(0.00);
            $table->decimal('carat_rate',18,3)->default(0.00);
            $table->decimal('total_amount',18,3)->default(0.00);
            $table->decimal('total_dollar',18,3)->default(0.00);
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
        Schema::dropIfExists('ratti_kaat_diamonds');
    }
};
