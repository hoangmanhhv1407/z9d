<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CassoTransactionHistory extends Model
{
    protected $table = 'cassotransactionhistory';

    protected $fillable = [
        'user_id',
        'transaction_id',
        'amount',
        'description',
        'transaction_time',
        'is_processed'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        'is_processed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}