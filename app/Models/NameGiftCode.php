<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class NameGiftCode extends Model
{
    protected $table = 'name_gift_code';

    public function giftCode()
    {
        return $this->belongsTo(GiftCode::class,'gift_code_id','id');
    }
//
//    public function user()
//    {
//        return $this->belongsTo(User::class,'user_id','id');
//    }
}
