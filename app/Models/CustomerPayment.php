<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerPayment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'customer_id',
        'account_id',
        'payment_date',
        'reference',
        'tax',
        'tax_amount',
        'tax_account_id',
        'sub_total',
        'currency',
        'total',
        'jv_id',
        'created_at',
        'updated_at',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'

    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function customer_name()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function account_name()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function tax_account_name()
    {
        return $this->belongsTo(Account::class, 'tax_account_id');
    }
    public function jv_name()
    {
        return $this->belongsTo(JournalEntry::class, 'jv_id');
    }
}
