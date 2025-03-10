<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CateBlogRequest;
use App\Models\CategoryBlog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
class CategoryBlogController extends Controller
{
    public function index()
    {
        $showCateBlog = CategoryBlog::paginate(10);
        return view('admin.cateBlog.index',compact('showCateBlog'));
    }

    public function add()
    {
        return view('admin.cateBlog.add');
    }

    public function store(CateBlogRequest $request)
    {

        $data = $request->except('_token');
        $data['cpo_slug'] = str_slug($request->cpo_name);
        $data['created_at'] = $data['updated_at'] = Carbon::now();
        $id = CategoryBlog::insert($data);
        if($id > 0)
        {
            return redirect()->route('admin.cateBlog.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.cateBlog.index')->with('danger',' The update was failed !!! ');
    }

    public function edit(Request $request,$id)
    {
        $blog = CategoryBlog::findOrFail($id);
        return view('admin.cateBlog.edit',compact('blog'));
    }

    public function update(CateBlogRequest $request,$id)
    {
        $data = $request->except('_token');
        $data['cpo_slug'] = str_slug($request->cpo_name);
        $data['updated_at'] = Carbon::now();
        $id = CategoryBlog::where('id',$id)->update($data);
        if($id > 0)
        {
            return redirect()->route('admin.cateBlog.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.cateBlog.index')->with('danger',' The update was failed !!! ');
    }

    public function delete($id)
    {
        $blog = CategoryBlog::findOrFail($id);
        $flagCheck = $blog->delete();
        if($flagCheck > 0)
        {
            return redirect()->back()->with('success',' The update was successful !!! ');
        }
        return redirect()->back()->with('danger',' The update was failed !!! ');
    }
    public function status($id,$status)
    {
        if($status == 1){
            CategoryBlog::where('id',$id)->update(['cpo_active' => 0]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }elseif($status == 0){
            CategoryBlog::where('id',$id)->update(['cpo_active' => 1]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }
        return redirect()->back()->with('danger','  The update was failed !!! ');

    }
}
