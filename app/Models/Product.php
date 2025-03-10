<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'prd_name',
        'prd_slug',
        'prd_description',
        'prd_hot',
        'prd_active',
        'prd_thunbar',
        'prd_category_product_id',
        'prd_status',
        'prd_info',
        'prd_code',
        'luckyStatus',
        'ratioLucky',
        'prd_content',
        'coin',
        'turn',
        'accumulate_status',
        'accu',
        'daily_gift_status',
        'daily_gift_type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'prd_hot' => 'boolean',
        'prd_active' => 'boolean',
        'prd_status' => 'integer',
        'luckyStatus' => 'integer',
        'ratioLucky' => 'float',
        'coin' => 'integer',
        'turn' => 'integer',
        'accumulate_status' => 'integer',
        'accu' => 'integer',
    ];

    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProduct::class, 'prd_category_product_id', 'id');
    }
}