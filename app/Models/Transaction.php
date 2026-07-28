<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'type',
        'date',
        'other_product_id',
        'qty',
        'unit_price',
        'other_purchase_id',
        'other_sale_id',
        'stock_taking_id',
        'stock_taking_link_id',
        'warehouse_id',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id',
        'created_at',
        'updated_at'
    ];
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    public function warehouse_name()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }
    public function other_sale()
    {
        return $this->belongsTo(OtherSale::class, 'other_sale_id');
    }

    public function other_purchase()
    {
        return $this->belongsTo(OtherPurchase::class, 'other_purchase_id');
    }

    public function stock_taking()
    {
        return $this->belongsTo(StockTaking::class, 'stock_taking_id');
    }

    public function other_product()
    {
        return $this->belongsTo(OtherProduct::class, 'other_product_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
