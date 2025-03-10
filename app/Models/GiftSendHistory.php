<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiftSendHistory extends Model
{
    protected $table = 'history_send_gift';

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'userId','id');
    }
}
