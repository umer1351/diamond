<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldImpurityPurchaseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'gold_impurity_purchase_id',
        'scale_weight',
        'bead_weight',
        'stone_weight',
        'net_weight',
        'point',
        'pure_weight',
        'gold_rate',
        'total_amount'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function gold_impurity_purchase()
    {
        return $this->belongsTo(GoldImpurityPurchase::class, 'gold_impurity_purchase_id');
    }
}
