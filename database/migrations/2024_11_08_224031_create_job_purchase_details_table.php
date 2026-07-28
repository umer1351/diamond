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
        Schema::create('job_purchase_details', function (Blueprint $table) {
            $table->id();
            $table->integer('job_purchase_id')->nullable();
            $table->integer('purchase_order_detail_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('category')->nullable();
            $table->string('design_no')->nullable();
            $table->decimal('waste_ratti',18,3)->default(0.00);
            $table->decimal('waste',18,3)->default(0.00);
            $table->decimal('polish_weight',18,3)->default(0.00);
            $table->decimal('stone_waste',18,3)->default(0.00)->comment('0.25/100 stones');
            $table->string('mail')->nullable()->comment('Upper, Inner');
            $table->decimal('mail_weight',18,3)->default(0.00);
            $table->decimal('stone_waste_weight',18,3)->default(0.00);
            $table->decimal('recieved_weight',18,3)->default(0.00);
            $table->decimal('total_recieved_weight',18,3)->default(0.00);
            $table->decimal('bead_weight',18,3)->default(0.00);
            $table->decimal('stones_weight',18,3)->default(0.00);
            $table->decimal('diamond_carat',18,3)->default(0.00);
            $table->decimal('with_stone_weight',18,3)->default(0.00);
            $table->decimal('total_weight',18,3)->default(0.00);
            $table->decimal('pure_weight',18,3)->default(0.00);
            $table->decimal('payable_weight',18,3)->default(0.00);
            $table->decimal('stone_adjustement',18,3)->default(0.00);
            $table->decimal('final_pure_weight',18,3)->default(0.00);
            $table->decimal('pure_payable',18,3)->default(0.00);
            $table->decimal('laker',18,3)->default(0.00);
            $table->decimal('rp',18,3)->default(0.00);
            $table->decimal('wax',18,3)->default(0.00);
            $table->decimal('other',18,3)->default(0.00);
            $table->decimal('total_bead_amount',18,3)->default(0.00);
            $table->decimal('total_stones_amount',18,3)->default(0.00);
            $table->decimal('total_diamond_amount',18,3)->default(0.00);
            $table->decimal('total_amount',18,3)->default(0.00);
            $table->decimal('total_dollar',18,3)->default(0.00);
            $table->boolean('is_finish_product')->default(0);
            $table->boolean('is_deleted')->default(0);
            $table->integer('approvedby_id')->nullable();
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
        Schema::dropIfExists('job_purchase_details');
    }
};
