<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeEventGift extends Model
{
    protected $fillable = [
        'event_type',
        'milestone_amount',
        'product_id'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}