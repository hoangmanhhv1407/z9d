<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiftSend extends Model
{
    protected $table = 'setting_send_gift';

//    public function nameGift()
//    {
//        return $this->hasMany(NameGiftCode::class,'gift_code_id','id');
//    }
//
//    public function user()
//    {
//        return $this->belongsTo(User::class,'user_id','id');
//    }
}
