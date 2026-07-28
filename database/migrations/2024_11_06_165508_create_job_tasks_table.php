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
        Schema::create('job_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('job_task_no')->nullable();
            $table->string('job_task_date')->nullable();
            $table->string('delivery_date')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('purchase_order_id')->nullable();
            $table->integer('sale_order_id')->nullable();
            $table->decimal('total_qty', 18, 3)->nullable();
            $table->boolean('is_complete')->default(0);
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
        Schema::dropIfExists('job_tasks');
    }
};
