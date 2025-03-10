<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeEventMilestone extends Model
{
    protected $table = 'recharge_event_milestones';
    
    protected $fillable = [
        'amount',
        'product_id'
    ];

    // Loại bỏ các trường claimed_by_user_id và claimed_at vì không có trong DB

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}