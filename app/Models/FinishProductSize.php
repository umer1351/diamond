<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinishProductSize extends Model
{
    use HasFactory;

    protected $fillable = [
        'finish_product_id',
        'tag_no',
        'size',
        'quantity',
    ];

    public function finishProduct()
    {
        return $this->belongsTo(FinishProduct::class, 'finish_product_id');
    }
}
