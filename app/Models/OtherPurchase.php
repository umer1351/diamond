<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherPurchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'other_purchase_no',
        'other_purchase_date',
        'supplier_id',
        'warehouse_id',
        'bill_no',
        'reference',
        'purchase_account_id',
        'total_qty',
        'tax',
        'tax_amount',
        'sub_total',
        'total',
        'is_credit',
        'paid',
        'paid_account_id',
        'jv_id',
        'paid_jv_id',
        'supplier_payment_id',
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
    public function OtherPurchaseDetail()
    {
        return $this->hasMany(OtherPurchaseDetail::class, 'other_purchase_id');
    }
    public function jv()
    {
        return $this->belongsTo(JournalEntry::class, 'jv_id');
    }
    public function paid_jv()
    {
        return $this->belongsTo(JournalEntry::class, 'paid_jv_id');
    }
    public function supplier_name()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function warehouse_name()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function purchase_account()
    {
        return $this->belongsTo(Account::class, 'purchase_account_id');
    }
    public function paid_account()
    {
        return $this->belongsTo(Account::class, 'paid_account_id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
