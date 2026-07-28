<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name',
        'parent_id',
        'account_type_id',
        'is_active',
        'opening_balance',
        'opening_cr',
        'opening_dr',
        'is_cash_account',
        'level',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'
    ];

    public function Account()
    {
        return $this->belongsToMany(Account::class);
    }
}
