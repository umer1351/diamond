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
        Schema::create('job_task_activities', function (Blueprint $table) {
            $table->id();
            $table->integer('job_task_id')->nullable();
            $table->string('category')->nullable();
            $table->string('design_no')->nullable();
            $table->decimal('weight', 18, 3)->default(0);
            $table->string('picture')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_deleted')->default(0);
            $table->integer('createdby_id')->nullable();
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
        Schema::dropIfExists('job_task_activities');
    }
};
