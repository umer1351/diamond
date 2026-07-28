<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPurchaseDetailStone extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'category',
        'product_id',
        'type',
        'job_purchase_detail_id',
        'stones',
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

    public function job_purchase_detail()
    {
        return $this->belongsTo(JobPurchaseDetail::class, 'job_purchase_detail_id');
    }

}
