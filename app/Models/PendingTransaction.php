<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PendingTransaction extends Model
{
    protected $fillable = ['user_id', 'amount', 'transaction_code', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}