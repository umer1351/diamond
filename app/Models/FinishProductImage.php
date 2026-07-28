<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'finish_product_id',
        'path',
        'sort_order',
    ];

    public function finish_product()
    {
        return $this->belongsTo(FinishProduct::class, 'finish_product_id');
    }
}
