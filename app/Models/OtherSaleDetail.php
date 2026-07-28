<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherSaleDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'other_sale_id',
        'other_product_id',
        'unit_price',
        'qty',
        'total_amount',
        'is_deleted',
        'createdby_id',
        'deletedby_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];
    public function other_sale()
    {
        return $this->belongsTo(OtherSale::class, 'other_sale_id');
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
