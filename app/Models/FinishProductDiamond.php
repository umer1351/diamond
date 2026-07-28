<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishProductDiamond extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'finish_product_id',
        'diamonds',
        'type',
        'cut',
        'color',
        'clarity',
        'carat',
        'carat_rate',
        'total_amount',
        'total_dollar',
        'is_deleted',
        'createdby_id',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function finish_product()
    {
        return $this->belongsTo(FinishProduct::class, 'finish_product_id');
    }
}
