<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTakingDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'stock_taking_id',
        'other_product_id',
        'quantity_in_stock',
        'actual_quantity',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'created_at',
        'updated_at'

    ];
    protected $dates = [
        'updated_at',
        'created_at',
    ];
    public function stock_taking()
    {
        return $this->belongsTo(StockTaking::class, 'stock_taking_id');
    }
    public function other_product()
    {
        return $this->belongsTo(OtherProduct::class, 'other_product_id');
    }
}
