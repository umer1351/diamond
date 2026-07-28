<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BeadType extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'name', 'createdby_id', 'updatedby_id', 'is_active', 'is_deleted', 'deletedby_id'];
    protected $dates = [
        'updated_at',
        'created_at'
    ];
}
