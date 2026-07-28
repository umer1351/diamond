<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherSale extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'other_sale_no',
        'other_sale_date',
        'customer_id',
        'customer_name',
        'customer_cnic',
        'customer_contact',
        'customer_email',
        'customer_address',
        'total_qty',
        'tax',
        'tax_amount',
        'sub_total',
        'total',
        'is_credit',
        'cash_amount',
        'bank_transfer_amount',
        'card_amount',
        'advance_amount',
        'total_received',
        'jv_id',
        'posted',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function other_product()
    {
        return $this->belongsTo(OtherProduct::class, 'other_product_id');
    }
    public function OtherSaleDetail()
    {
        return $this->hasMany(OtherSaleDetail::class, 'other_sale_id');
    }
    public function jv()
    {
        return $this->belongsTo(JournalEntry::class, 'jv_id');
    }
    public function customer_name()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
