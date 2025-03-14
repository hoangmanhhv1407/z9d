<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LuckyNumberSendHistory extends Model
{
    protected $table = 'history_send_lucky_number';

//    public function product()
//    {
//        return $this->belongsTo(Product::class,'product_id','id');
//    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
