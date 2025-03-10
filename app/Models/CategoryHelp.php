<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryHelp extends Model
{
    protected $table = 'category_help';
    public function help()
    {
        return $this->hasMany(Help::class,'h_category_id','id');
    }
}
