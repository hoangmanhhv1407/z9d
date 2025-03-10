<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Blog extends Model
{
    protected $table = 'blog';
    
    protected $fillable = [
        'b_name',
        'b_slug',
        'b_description',
        'b_content',
        'b_category_id',
        'b_thunbar',
        'b_status',
        'hot'
    ];

    public function categoryBlog()
    {
        return $this->belongsTo(CategoryBlog::class, 'b_category_id', 'id');
    }
    public function convertSlug($slug)
    {
        $pattern = '/(?<=-)(\d+)$/i';
        preg_match($pattern, $slug, $match);
        if (isset($match[1])) {
            $id = $match[1];
            return $id;
        }
    }
}
