<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'company_id',
        'purchase_account_id',
        'sale_account_id',
        'cash_account_id',
        'revenue_account_id',
        'bank_account_id',
        'card_account_id',
        'customer_account_id',
        'supplier_account_id',
        'recieving_account_id',
        'discount_account_id',
        'createdby_id',
        'updatedby_id',
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];
}
