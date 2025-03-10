<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $table = 'password_resets';

    protected $fillable = [
        'email', 'token'
    ];
//    public function product()
//    {
//        return $this->belongsTo(Product::class,'product_id','id');
//    }
//
//    public function user()
//    {
//        return $this->belongsTo(User::class,'user_id','id');
//    }
}
