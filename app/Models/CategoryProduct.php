<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $table = 'category_product';

    public function product()
    {
        return $this->hasMany(Product::class,'prd_category_product_id','id');
    }
}
