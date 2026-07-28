<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'prefix',
        'image_path',
        'description',
        'tags',
        'attributes',
        'stock',
        'createdby_id',
        'updatedby_id',
        'is_active',
        'is_deleted',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function finishProducts()
    {
        return $this->hasMany(FinishProduct::class, 'product_id');
    }

    public function getStockAttribute($value)
    {
        if ($value !== null && $value !== '') {
            return (float) $value;
        }

        return (float) $this->finishProducts()->where('is_deleted', 0)->where('is_active', 1)->count();
    }
}
