<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class NDV02InvenTableBase extends Model
{
    protected $connection = 'cuuamsql2';
    public $timestamps = false;

    protected $fillable = [
        'cuid',
        'unique_id',
        'slot',
        'item_type',
        'item_id',
        'item_count',
        'durability',
        'socket_count',
        'socket_item',
        'inchant',
        'protect',
        'end_date',
        'add_option',
        'check_field'
    ];

    protected $casts = [
        'cuid' => 'integer',
        'unique_id' => 'integer',
        'slot' => 'integer',
        'item_type' => 'integer',
        'item_id' => 'integer',
        'item_count' => 'integer',
        'durability' => 'integer',
        'socket_count' => 'integer',
        'end_date' => 'datetime',
        'check_field' => 'integer',
    ];

    /**
     * Lấy character liên quan
     */
    public function character()
    {
        return $this->belongsTo(NDV01CharacState::class, 'cuid', 'unique_id');
    }
}