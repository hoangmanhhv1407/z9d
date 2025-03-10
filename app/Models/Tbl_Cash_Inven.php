<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tbl_Cash_Inven extends Model
{
    protected $connection = 'cuuamsql';
    public $timestamps = false;
    protected $table = 'Tbl_Cash_Inven';

    protected $fillable = [
        'order_idx',
        'item_code',
        'item_user_id',
        'item_server_code',
        'item_present',
        'order_input_date'
    ];
}