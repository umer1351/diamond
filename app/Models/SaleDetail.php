<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'sale_id',
        'finish_product_id',
        'job_purchase_detail_id',
        'ratti_kaat_id',
        'ratti_kaat_detail_id',
        'product_id',
        'gold_carat',
        'scale_weight',
        'bead_weight',
        'stones_weight',
        'diamond_weight',
        'net_weight',
        'waste',
        'gross_weight',
        'making',
        'bead_price',
        'stones_price',
        'diamond_price',
        'total_bead_price',
        'total_stones_price',
        'total_diamond_price',
        'other_amount',
        'gold_rate',
        'total_gold_price',
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
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function finish_product()
    {
        return $this->belongsTo(FinishProduct::class, 'finish_product_id');
    }
    public function ratti_kaat()
    {
        return $this->belongsTo(RattiKaat::class, 'ratti_kaat_id');
    }

    public function ratti_kaat_detail()
    {
        return $this->belongsTo(RattiKaatDetail::class, 'ratti_kaat_detail_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
