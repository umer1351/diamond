<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'sale_order_no',
        'sale_order_date',
        'delivery_date',
        'customer_id',
        'warehouse_id',
        'total_qty',
        'gold_rate',
        'gold_rate_type_id',
        'is_purchased',
        'is_saled',
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

    public function SaleOrderDetail()
    {
        return $this->hasMany(SaleOrderDetail::class, 'sale_order_id');
    }

    public function gold_rate_type()
    {
        return $this->belongsTo(GoldRateType::class, 'gold_rate_type_id');
    }

    public function warehouse_name()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
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
