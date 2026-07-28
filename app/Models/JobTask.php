<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_task_no',
        'job_task_date',
        'delivery_date',
        'purchase_order_id',
        'sale_order_id',
        'supplier_id',
        'warehouse_id',
        'total_qty',
        'is_complete',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function JobTaskDetail()
    {
        return $this->hasMany(JobTaskDetail::class, 'job_task_id');
    }

    public function warehouse_name()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function purchase_order()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }
    public function sale_order()
    {
        return $this->belongsTo(SaleOrder::class, 'sale_order_id');
    }

    public function supplier_name()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
