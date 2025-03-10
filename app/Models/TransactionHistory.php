<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class TransactionHistory extends Model
{
    protected $table = 'transaction_history';


    protected $fillable = [
        'user_id',
        'admin_id',
        'product_id',
        'coin',
		'raw_coin', // Thêm trường này
        'type',
        'accumulate',
        'code',
        'qty',
        'recharge'  // Thêm trường này
    ];
    public function product()
    {
        return $this->belongsTo(Product::class,'product_id','id');
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    public function userAdmin()
    {
        return $this->belongsTo(User::class,'admin_id','id');
    }
}
