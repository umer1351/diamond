<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'employee_id',
        'name',
        'cnic',
        'gender',
        'date_of_birth',
        'contact',
        'email',
        'address',
        'emergency_name',
        'emergency_contact',
        'emergency_relation',
        'job_role',
        'department',
        'employee_type',
        'date_of_joining',
        'shift',
        'salary',
        'payment_method',
        'bank_name',
        'account_title',
        'account_no',
        'is_overtime',
        'sick_leave',
        'casual_leave',
        'annual_leave',
        'picture',
        'account_id',
        'is_active',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function account_name()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
}
