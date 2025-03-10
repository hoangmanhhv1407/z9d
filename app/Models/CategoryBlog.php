<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CategoryBlog extends Model
{
    protected $table = 'category_blog';
    protected $fillable = [
        'cpo_name',
        'cpo_slug',
        'cpo_description',
        'cpo_active'
    ];
    public function blog()
    {
        return $this->hasMany(Blog::class,'b_category_id','id');
    }
}
