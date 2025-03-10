<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeEventConfig extends Model
{
    protected $table = 'recharge_event_configs';
    
    protected $fillable = [
        'first_recharge_status',
        'milestone_status',
        'golden_hour_status',
        'golden_hour_start_time',
        'golden_hour_end_time',
        'golden_hour_multiplier',
        'first_recharge_start_time',
        'first_recharge_end_time',
        'first_recharge_gift_id',
        'milestone_start_time',
        'milestone_end_time',
        
    ];

    public function firstRechargeGifts()
    {
        return $this->hasMany(RechargeEventGift::class)
                    ->where('event_type', 'first_recharge');
    }

    public function milestoneGifts()
    {
        return $this->hasMany(RechargeEventGift::class)
                    ->where('event_type', 'milestone');
    }
}