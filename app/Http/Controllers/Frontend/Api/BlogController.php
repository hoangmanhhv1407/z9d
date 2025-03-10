<?php

namespace App\Http\Controllers\Frontend\Api;

use App\User;
use App\Models\Blog;
use App\Models\CategoryBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    protected $blog;
    protected $categoryBlog;

    public function __construct(Blog $blog, CategoryBlog $categoryBlog)
    {
        $this->blog = $blog;
        $this->categoryBlog = $categoryBlog;
    }

    public function getBlogs()
    {
        try {
            $categoryBlogList = CategoryBlog::where('cpo_active', 1)
                ->with(['blog' => function ($query) {
                    $query->where('b_status', 1);
                }])
                ->get();

            $blog = Blog::where('hot', 1)
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'categoryBlogList' => $categoryBlogList,
                'blog' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải dữ liệu'
            ], 500);
        }
    }

    public function getBlogDetail($slug)
    {
        try {
            $postDetail = $this->blog->where('b_slug', $slug)->first();
            if (!$postDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy bài viết'
                ], 404);
            }

            $postType = CategoryBlog::where('id', $postDetail->b_category_id)->first();
            $postHot = $this->blog->where('b_status', 1)->where('hot', 1)->get();

            return response()->json([
                'success' => true,
                'postDetail' => $postDetail,
                'postType' => $postType,
                'postHot' => $postHot
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải chi tiết bài viết'
            ], 500);
        }
    }

    public function getBlogsByCategory($id)
    {
        try {
            $postHot = $this->blog->where('b_status', 1)->where('hot', 1)->get();
            $query = $this->blog;

            if ($id != 0) {
                $query = $query->where('b_category_id', $id);
                $categoryBlog = $this->categoryBlog->where('id', $id)->first();
            }

            $postList = $query->paginate(16);

            return response()->json([
                'success' => true,
                'postList' => $postList,
                'postHot' => $postHot,
                'categoryBlog' => $categoryBlog ?? null
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi tải danh sách bài viết'
            ], 500);
        }
    }
}