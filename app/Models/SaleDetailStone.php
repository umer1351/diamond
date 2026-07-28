<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetailStone extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'category',
        'type',
        'sale_detail_id',
        'product_id',
        'stones',
        'gram',
        'carat',
        'gram_rate',
        'carat_rate',
        'total_amount',
        'is_deleted',
        'createdby_id',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function sale_detail()
    {
        return $this->belongsTo(SaleDetail::class, 'sale_detail_id');
    }
    public function product_name()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
