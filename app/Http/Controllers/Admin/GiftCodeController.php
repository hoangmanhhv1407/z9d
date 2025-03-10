<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BlogRequest;
use App\Http\Requests\GiftCodeRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Blog;
use App\Models\CategoryBlog;
use App\Models\CategoryProduct;
use App\Models\GiftCode;
use App\Models\NameGiftCode;
use App\Models\Product;
use App\Models\TransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiftCodeController extends Controller
{
//    public function index(Request $request)
//    {
//        $showHistory = TransactionHistory::with('product');
//        $total = TransactionHistory::orderBy('id', 'desc');
//        if ($request->type) {
//            $showHistory = $showHistory->where('type', $request->type);
//            $total = $total->where('type', $request->type);
//        }
//        if ($request->name) {
//            $user = User::where('userid', $request->name)->value('id');
//            $showHistory = $showHistory->where('user_id', $user);
//            $total = $total->where('user_id', $user);
//        }
//        $total = $total->sum('coin');
//
//        $showHistory = $showHistory->with('user')->orderBy('id', 'desc')->paginate(10);
//        $dataView = [
//            'showHistory' => $showHistory,
//            'total' => $total,
//            'query' => $request->query()
//        ];
//        return view('admin.giftCode.index', $dataView);
//    }
    public function index(Request $request)
    {
//        $showBlog = Blog::where('b_name','like','%'.$request->name.'%')
//            ->where('b_category_id','like','%'.$request->category_blog.'%')
//            ->with(['categoryBlog'=>function ($query){
//                $query->where('cpo_active',1);
//            }])->orderBy('id','desc')->paginate(10);


        $giftCode = GiftCode::orderBy('id','desc')->with('nameGift');
        if($request->name){
            $giftCode = $giftCode->where('gift_code','like','%'.$request->name.'%');
        }
        $giftCode = $giftCode->paginate(10);
//        dd($giftCode);
        $dataView = [
            'giftCode' => $giftCode,
            'query'       => $request->query()
        ];
        return view('admin.giftCode.index',$dataView);
    }

    public function add()
    {
        return view('admin.giftCode.add');
    }

    public function store(GiftCodeRequest $request)
    {
        $data = $request->except('_token');

        $dataImport = [
            'qty' => $data['qty'],
            'gift_code' => $data['giftcode'],
            'content' => $data['content'],
            'status' => $data['status'],
            'type' => $data['type'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ];
        $id = GiftCode::insert($dataImport);

        $nameGiftcode = preg_split('/\r\n|\r|\n/', $data['nameGiftcode']);
        $arr = [];
        foreach ($nameGiftcode as $value){
            array_push($arr,[
                'name' => $value,
                'gift_code_id' => GiftCode::max('id'),

            ]);
        }
        NameGiftCode::insert($arr);
        if($id > 0)
        {
            return redirect()->route('admin.giftCode.index')->with('success',' Tạo giftcode thành công !!! ');
        }
        return redirect()->route('admin.giftCode.index')->with('danger',' Tạo giftcode thất bại !!! ');
    }

    public function edit(Request $request,$id)
    {
        $blog = GiftCode::findOrFail($id);
        $nameGiftCode = NameGiftCode::where('gift_code_id',$id)->get();
        $arrName = [];
        foreach ($nameGiftCode as $value){
            array_push($arrName,$value->name);
        }
        $arr = implode("\n", $arrName);
//        $arr = str_replace("\n","<br />", $arrName);
        return view('admin.giftCode.edit',compact('blog','arr'));
    }

    public function update(Request $request,$id)
    {
        $data = $request->except('_token');
//        NameGiftCode::where('gift_code_id',  $id)->delete();
//        $nameGiftcode = preg_split('/\r\n|\r|\n/', $data['nameGiftcode']);
//        $arr = [];
//        foreach ($nameGiftcode as $value){
//            array_push($arr,[
//                'name' => $value,
//                'gift_code_id' => $id,
//
//            ]);
//        }
//        NameGiftCode::insert($arr);

        $dataImport = [
            'qty' => $data['qty'],
            'gift_code' => $data['giftcode'],
            'content' => $data['content'],
            'status' => $data['status'],
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ];
        $id = GiftCode::where('id',$id)->update($dataImport);
        if($id > 0)
        {
            return redirect()->route('admin.giftCode.index')->with('success',' The update was successful !!! ');
        }
        return redirect()->route('admin.giftCode.index')->with('danger',' The update was failed !!! ');
    }

    public function delete($id)
    {
        $blog = GiftCode::findOrFail($id);
        $flagCheck = $blog->delete();
        if($flagCheck > 0)
        {
            return redirect()->back()->with('success',' The update was successful !!! ');
        }
        return redirect()->back()->with('danger',' The update was failed !!! ');
    }
//    public function status($id,$status)
//    {
//        if($status == 1){
//            Blog::where('id',$id)->update(['b_status' => 0]);
//            return redirect()->back()->with('success',' The update was successful !!! ');
//        }elseif($status == 0){
//            Blog::where('id',$id)->update(['b_status' => 1]);
//            return redirect()->back()->with('success',' The update was successful !!! ');
//        }
//        return redirect()->back()->with('danger','  The update was failed !!! ');
//
//    }
//    public function hot($id,$hot)
//    {
////        dd($id,$hot);
//        if($hot == 1){
//            Blog::where('id',$id)->update(['hot' => 0]);
//            return redirect()->back()->with('success',' The update was successful !!! ');
//        }elseif($hot == 0){
//            Blog::where('id',$id)->update(['hot' => 1]);
//            return redirect()->back()->with('success',' The update was successful !!! ');
//        }
//        return redirect()->back()->with('danger','  The update was failed !!! ');
//
//    }
}
