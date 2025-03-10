<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class GiftCodeHistory extends Model
{
    protected $table = 'gift_code_history';

    public function giftCodeName()
    {
        return $this->belongsTo(NameGiftCode::class,'gift_code_name_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
