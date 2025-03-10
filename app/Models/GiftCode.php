<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiftCode extends Model
{
    protected $table = 'gift_code';

    public function nameGift()
    {
        return $this->hasMany(NameGiftCode::class,'gift_code_id','id');
    }
//
//    public function user()
//    {
//        return $this->belongsTo(User::class,'user_id','id');
//    }
}
