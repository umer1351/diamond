<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPurchaseDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'job_purchase_id',
        'purchase_order_detail_id',
        'product_id',
        'category',
        'design_no',
        'waste_ratti',
        'waste',
        'polish_weight',
        'stone_waste',
        'mail',
        'mail_weight',
        'stone_waste_weight',
        'recieved_weight',
        'total_recieved_weight',
        'bead_weight',
        'stones_weight',
        'diamond_carat',
        'with_stone_weight',
        'total_weight',
        'pure_weight',
        'payable_weight',
        'stone_adjustement',
        'final_pure_weight',
        'approvedby_id',
        'pure_payable',
        'laker',
        'rp',
        'wax',
        'other',
        'total_bead_amount',
        'total_stones_amount',
        'total_diamond_amount',
        'total_amount',
        'total_dollar',
        'is_finish_product',
        'is_deleted',
        'createdby_id',
        'updatedby_id',
        'deletedby_id'
    ];
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    public function job_purchase()
    {
        return $this->belongsTo(JobPurchase::class, 'job_purchase_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function approved_by()
    {
        return $this->belongsTo(User::class, 'approvedby_id');
    }
}
