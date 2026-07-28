<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RattiKaatBead extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'ratti_kaat_detail_id',
        'type',
        'ratti_kaat_id',
        'product_id',
        'beads',
        'gram',
        'carat',
        'gram_rate',
        'carat_rate',
        'total_amount',
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
    public function ratti_kaat_detail_name()
    {
        return $this->belongsTo(RattiKaatDetail::class, 'ratti_kaat_detail_id');
    }
    public function product_name()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
