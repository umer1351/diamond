<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'contact',
        'cnic',
        'company',
        'type',
        'account_id',
        'account_au_id',
        'account_dollar_id',
        'is_active',
        'gold_waste',
        'stone_waste',
        'kaat',
        'bank_name',
        'account_title',
        'account_no',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'
    ];

    public function account_name()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }
    public function account_au_name()
    {
        return $this->belongsTo(Account::class, 'account_au_id');
    }
    public function account_dollar_name()
    {
        return $this->belongsTo(Account::class, 'account_dollar_id');
    }
}
