<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPurchaseDetailDiamond extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_purchase_detail_id',
        'product_id',
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
