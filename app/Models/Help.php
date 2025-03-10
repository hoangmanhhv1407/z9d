<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Help extends Model
{
    protected $table = 'help';

    public function categoryHelp()
    {
        return $this->belongsTo(CategoryHelp::class,'h_category_id','id');
    }

    public function convertSlug($slug)
    {
        $pattern = '/(?<=-)(\d+)$/i';
        preg_match($pattern, $slug, $match);

        if (isset($match[1]))
        {
            $id = $match[1];
            return $id;
        }
    }
}
