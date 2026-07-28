<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishProductStone extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'category',
        'type',
        'finish_product_id',
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

    public function finish_product()
    {
        return $this->belongsTo(FinishProduct::class, 'finish_product_id');
    }
}
