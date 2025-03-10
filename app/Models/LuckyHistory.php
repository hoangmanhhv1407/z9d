<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class LuckyHistory extends Model
{
    protected $table = 'lucky-history';

    public function product()
    {
        return $this->belongsTo(Product::class,'productId','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'userId','id');
    }
}
