<?php

namespace App\Http\Controllers\Admin;

use App\Models\CategoryBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Models\Blog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $showBlog = Blog::where('b_name', 'like', '%' . $request->name . '%')
            ->where('b_category_id', 'like', '%' . $request->category_blog . '%')
            ->with(['categoryBlog' => function ($query) {
                $query->where('cpo_active', 1);
            }])
            ->orderBy('id', 'desc')
            ->paginate(10);

        $category = CategoryBlog::where('cpo_active', 1)->get();
        
        $dataView = [
            'showBlog' => $showBlog,
            'category' => $category,
            'query' => $request->query()
        ];
        
        return view('admin.blog.index', $dataView);
    }

    public function add()
    {
        $category = CategoryBlog::where('cpo_active', 1)->get();
        return view('admin.blog.add', compact('category'));
    }

    public function store(BlogRequest $request)
    {
        try {
            $data = $request->validated();
            $data['b_content'] = mb_convert_encoding($data['b_content'], 'UTF-8', 'auto');
            if ($request->hasFile('b_thunbar')) {
                $file = $request->file('b_thunbar');
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('blogs', $file, $filename);
                $data['b_thunbar'] = $filename;
            }

            $data['b_slug'] = str_slug($request->b_name);
            $data['created_at'] = $data['updated_at'] = Carbon::now();

            Blog::create($data);
            
            return redirect()
                ->route('admin.blogs.index')
                ->with('success', 'Blog created successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to create blog: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = CategoryBlog::where('cpo_active', 1)->get();
            $blog = Blog::findOrFail($id);
            return view('admin.blog.edit', compact('blog', 'category'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.blogs.index')
                ->with('danger', 'Blog not found!');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $data = $request->all();

            if ($request->hasFile('b_thunbar')) {
                // Delete old image if exists
                if ($blog->b_thunbar) {
                    Storage::disk('public')->delete('blogs/' . $blog->b_thunbar);
                }

                // Upload new image
                $file = $request->file('b_thunbar');
                $filename = time() . '_' . $file->getClientOriginalName();
                Storage::disk('public')->putFileAs('blogs', $file, $filename);
                $data['b_thunbar'] = $filename;
            }

            $data['b_slug'] = str_slug($request->b_name);
            $data['updated_at'] = Carbon::now();

            $blog->update($data);
            
            return redirect()
                ->route('admin.blogs.index')
                ->with('success', 'Blog updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to update blog: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function delete($id)
    {
        try {
            $blog = Blog::findOrFail($id);

            // Delete associated image if exists
            if ($blog->b_thunbar) {
                Storage::disk('public')->delete('blogs/' . $blog->b_thunbar);
            }

            $blog->delete();
            
            return redirect()
                ->back()
                ->with('success', 'Blog deleted successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to delete blog: ' . $e->getMessage());
        }
    }

    public function status($id, $status)
    {
        try {
            $newStatus = $status == 1 ? 0 : 1;
            Blog::where('id', $id)->update(['b_status' => $newStatus]);
            
            return redirect()
                ->back()
                ->with('success', 'Blog status updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to update blog status!');
        }
    }

    public function hot($id, $hot)
    {
        try {
            $newHotStatus = $hot == 1 ? 0 : 1;
            Blog::where('id', $id)->update(['hot' => $newHotStatus]);
            
            return redirect()
                ->back()
                ->with('success', 'Blog hot status updated successfully!');

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to update blog hot status!');
        }
    }
}