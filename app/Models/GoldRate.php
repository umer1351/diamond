<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GoldRate extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'carat',
        'gold',
        'impurity',
        'ratti',
        'ratti_impurity',
        'rate_tola',
        'rate_gram',
        'createdby_id',
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function created_by()
    {
        return $this->belongsTo(User::class, 'createdby_id');
    }
}
