<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FirstRechargeClaim extends Model
{
    protected $table = 'first_recharge_claims';
    
    protected $fillable = [
        'user_id',
        'claimed_at'
    ];

    protected $dates = [
        'claimed_at',
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}