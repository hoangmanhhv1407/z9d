<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CateHelpRequest;
use App\Models\CategoryHelp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
class CategoryHelpController extends Controller
{
    public function index()
    {
        $showCateBlog = CategoryHelp::paginate(10);
        return view('admin.cateHelp.index',compact('showCateBlog'));
    }

    public function add()
    {
        return view('admin.cateHelp.add');
    }

    public function store(CateHelpRequest $request)
    {

        $data = $request->except('_token');
        $data['ch_slug'] = str_slug($request->ch_name);
        $data['created_at'] = $data['updated_at'] = Carbon::now();
        $id = CategoryHelp::insert($data);
        if($id > 0)
        {
            return redirect()->route('admin.cateHelp.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.cateHelp.index')->with('danger',' The update was failed !!! ');
    }

    public function edit(Request $request,$id)
    {
        $blog = CategoryHelp::findOrFail($id);
        return view('admin.cateHelp.edit',compact('blog'));
    }

    public function update(CateHelpRequest $request,$id)
    {
        $data = $request->except('_token');
        $data['ch_slug'] = str_slug($request->ch_name);
        $data['updated_at'] = Carbon::now();
        $id = CategoryHelp::where('id',$id)->update($data);
        if($id > 0)
        {
            return redirect()->route('admin.cateHelp.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.cateHelp.index')->with('danger',' The update was failed !!! ');
    }

    public function delete($id)
    {
        $blog = CategoryHelp::findOrFail($id);
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
            CategoryHelp::where('id',$id)->update(['ch_active' => 0]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }elseif($status == 0){
            CategoryHelp::where('id',$id)->update(['ch_active' => 1]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }
        return redirect()->back()->with('danger','  The update was failed !!! ');

    }
}
