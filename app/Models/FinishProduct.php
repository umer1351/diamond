<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_purchase_id',
        'ratti_kaat_id',
        'ratti_kaat_detail_id',
        'job_purchase_detail_id',
        'tag_no',
        'parent_id',
        'is_parent',
        'barcode',
        'product_id',
        'product_name',
        'warehouse_id',
        'picture',
        'short_description',
        'long_description',
        'tags',
        'sizes',
        'stock_quantity',
        'gold_carat',
        'scale_weight',
        'bead_weight',
        'stones_weight',
        'diamond_weight',
        'net_weight',
        'waste_per',
        'waste',
        'gross_weight',
        'making_gram',
        'making',
        'laker',
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
        'is_active',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];
    public function FinishProduct()
    {
        return $this->belongsToMany(FinishProduct::class);
    }

    public function images()
    {
        return $this->hasMany(FinishProductImage::class, 'finish_product_id')
            ->orderBy('sort_order')
            ->orderBy('id');
    }
    public function parent_name()
    {
        return $this->belongsTo(FinishProduct::class, 'parent_id');
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

    public function job_purchase_detail()
    {
        return $this->belongsTo(JobPurchaseDetail::class, 'job_purchase_detail_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
