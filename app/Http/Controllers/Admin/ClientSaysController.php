<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ClientSaysRequest;
use App\Models\ClientSays;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
class ClientSaysController extends Controller
{
    public function index()
    {
        $showBlog = ClientSays::paginate(10);
        return view('admin.clientSays.index',compact('showBlog'));
    }

    public function add()
    {
        return view('admin.clientSays.add');
    }

    public function store(ClientSaysRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('cs_thunbar'))
        {
            $info = uploadImg('cs_thunbar');
            if($info['code'] == 1)
            {
                $data['cs_thunbar'] = $info['name'];
                move_uploaded_file($_FILES['cs_thunbar']['tmp_name'], public_path() . '/uploads/imgClientSays/' . $info['name']);
            }
        }
        $data['created_at'] = $data['updated_at'] = Carbon::now();
        unset($data['_token']);
        $id = ClientSays::insert($data);
        if($id > 0)
        {
            return redirect()->route('admin.clientSays.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.clientSays.index')->with('danger',' The update was failed !!! ');
    }

    public function edit(Request $request,$id)
    {
        $blog = ClientSays::findOrFail($id);
        return view('admin.clientSays.edit',compact('blog'));
    }

    public function update(Request $request,$id)
    {
        $data = $request->all();
        unset($data['_token']);

        if ($request->hasFile('cs_thunbar'))
        {
            $info = uploadImg('cs_thunbar');
            if($info['code'] == 1)
            {
                $data['cs_thunbar'] = $info['name'];
                move_uploaded_file($_FILES['cs_thunbar']['tmp_name'], public_path() . '/uploads/imgClientSays/' . $info['name']);
            }

        }else{
            $data['cs_thunbar'] = ClientSays::findOrFail($id)->cs_thunbar;
        }
        $data['updated_at'] = Carbon::now();
        $id = ClientSays::where('id',$id)->update($data);
        if($id > 0)
        {
            return redirect()->route('admin.clientSays.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.clientSays.index')->with('danger',' The update was failed !!! ');
    }

    public function delete($id)
    {
        $blog = ClientSays::findOrFail($id);
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
            ClientSays::where('id',$id)->update(['cs_status' => 0]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }elseif($status == 0){
            ClientSays::where('id',$id)->update(['cs_status' => 1]);
            return redirect()->back()->with('success',' The update was successful !!! ');
        }
        return redirect()->back()->with('danger','  The update was failed !!! ');

    }
}
