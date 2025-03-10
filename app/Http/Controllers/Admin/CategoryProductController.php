<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CateProductRequest;
use App\Models\CategoryProduct;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryProductController extends Controller
{
    public function index()
    {
        $showCateProduct = CategoryProduct::paginate(10);
        return view('admin.cateProduct.index',compact('showCateProduct'));
    }

    public function add()
    {
        return view('admin.cateProduct.add');
    }

    public function store(CateProductRequest $request)
    {
        $data = $request->except('_token');
        $data['cpr_slug'] = str_slug($request->cpr_name);
        $data['created_at'] = $data['updated_at'] = Carbon::now();
        $id = CategoryProduct::insert($data);
        if($id > 0)
        {
            return redirect()->route('admin.cateProduct.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.cateProduct.index')->with('danger',' The update was failed !!! ');
    }

    public function edit(Request $request,$id)
    {
        $editCateProduct = CategoryProduct::findOrFail($id);
        return view('admin.cateProduct.edit',compact('editCateProduct'));
    }

    public function update(CateProductRequest $request,$id)
    {
        $data = $request->except('_token');
        $data['cpr_slug'] = str_slug($request->cpr_name);
        $data['updated_at'] = Carbon::now();
        $id = CategoryProduct::where('id',$id)->update($data);
        if($id > 0)
        {
            return redirect()->route('admin.cateProduct.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.cateProduct.index')->with('danger',' The update was failed !!! ');
    }

    public function delete($id)
    {
        $blog = CategoryProduct::findOrFail($id);
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
            CategoryProduct::where('id',$id)->update(['cpr_active' => 0]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }elseif($status == 0){
            CategoryProduct::where('id',$id)->update(['cpr_active' => 1]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }
        return redirect()->back()->with('danger','  The update was failed !!! ');

    }
}
