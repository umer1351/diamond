<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RattiKaatDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'ratti_kaat_id',
        'product_id',
        'description',
        'scale_weight',
        'bead_weight',
        'stones_weight',
        'dimand_carat',
        'net_weight',
        'supplier_kaat',
        'kaat',
        'approved_by',
        'pure_payable',
        'other_charge',
        'total_bead_amount',
        'total_stones_amount',
        'total_diamond_amount',
        'total_amount',
        'total_dollar',
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

    public function ratti_kaat_name()
    {
        return $this->belongsTo(RattiKaat::class, 'ratti_kaat_id');
    }
    public function product_name()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
