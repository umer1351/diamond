<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPurchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_task_id',
        'job_purchase_no',
        'job_purchase_date',
        'purchase_order_id',
        'sale_order_id',
        'supplier_id',
        'warehouse_id',
        'reference',
        'total_recieved_au',
        'total',
        'total_au',
        'total_dollar',
        'jv_id',
        'jv_au_id',
        'jv_dollar_id',
        'jv_recieved_id',
        'supplier_au_payment_id',
        'is_posted',
        'is_saled',
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
    public function JobPurchaseDetail()
    {
        return $this->hasMany(JobPurchaseDetail::class, 'job_purchase_id');
    }
    public function purchase_account()
    {
        return $this->belongsTo(Account::class, 'purchase_account_id');
    }
    public function supplier_name()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    
    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function sale_order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
