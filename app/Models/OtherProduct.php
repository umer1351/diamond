<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'code',
        'name',
        'other_product_unit_id',
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

    public function other_product_unit()
    {
        return $this->belongsTo(OtherProductUnit::class, 'other_product_unit_id');
    }
}
