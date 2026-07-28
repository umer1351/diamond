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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('cnic')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('contact')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('emergency_name')->nullable();
            $table->string('emergency_contact')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('job_role')->nullable();
            $table->string('department')->nullable();
            $table->string('employee_type')->nullable()->comment('Full-time, Part-time, Contract');
            $table->date('date_of_joining')->nullable();
            $table->string('shift')->nullable()->comment('shift time in string');
            $table->decimal('salary')->default(0);
            $table->string('payment_method')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_title')->nullable();
            $table->string('account_no')->nullable();
            $table->boolean('is_overtime')->default(0);
            $table->decimal('sick_leave')->default(0);
            $table->decimal('casual_leave')->default(0);
            $table->decimal('annual_leave')->default(0);
            $table->string('picture')->nullable();
            $table->integer('account_id')->nullable();
            $table->boolean('is_active')->default(1)->comment('1 for active, 0 for inactive');
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
        Schema::dropIfExists('employees');
    }
};
