<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BlogRequest;
use App\Http\Requests\GiftCodeRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Blog;
use App\Models\CategoryBlog;
use App\Models\CategoryProduct;
use App\Models\GiftCode;
use App\Models\GiftSend;
use App\Models\GiftSendHistory;
use App\Models\NameGiftCode;
use App\Models\Product;
use App\Models\TransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiftSendController extends Controller
{

    public function index(Request $request)
    {
        $giftSend = GiftSend::get();
        $productList = Product::where('prd_status', 1)->get();
//        dd($product);


//        $listUserNap
        $listUserNap = User::with(['transactionHistory' => function ($query) {
            $query->whereIn('type', [1, 3]);
        }])->whereHas('transactionHistory')->get();

        $listData = [];
        foreach ($listUserNap as $key => $value) {
//            dd($value);
            $total = 0;
            foreach ($value->transactionHistory as $item) {
                $total += $item->coin;
            }
            $listData[$key]['total'] = $total;
            $listData[$key]['userId'] = $value->id;

        }
        $collection = collect($listData)->SortByDesc('total')->values()->all();
//        dd($collection);

        foreach ($collection as $key => $value) {
            if ($value['total'] >= 100) {
                $product = GiftSend::where('giftCoin', 100)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 100)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,
                        'giftCoin' => 100,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 200) {
                $product = GiftSend::where('giftCoin', 200)->first();

                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 200)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,

                        'giftCoin' => 200,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 500) {
                $product = GiftSend::where('giftCoin', 500)->first();

                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 500)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,

                        'giftCoin' => 500,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 1000) {
                $product = GiftSend::where('giftCoin', 1000)->first();

                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 1000)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,

                        'giftCoin' => 1000,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 2000) {
                $product = GiftSend::where('giftCoin', 2000)->first();

                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 2000)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,

                        'giftCoin' => 2000,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 5000) {
                $product = GiftSend::where('giftCoin', 5000)->first();

                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 5000)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,

                        'giftCoin' => 5000,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 10000) {
                $product = GiftSend::where('giftCoin', 10000)->first();

                $check = GiftSendHistory::where('userId', $value['userId'])->where('giftCoin', 10000)->first();
                if (!$check &&$product&& $product->product != null) {
                    $dataImport = [
                        'product_id' => $product->product,
                        'luckyNumber' => $product->luckyNumber,

                        'giftCoin' => 10000,
                        'userId' => $value['userId'],
                        'status' => 3,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                    GiftSendHistory::insert($dataImport);
                }
            }

        }
        $dataView = [
            'giftSend' => $giftSend,
            'product' => $productList,
        ];
        return view('admin.giftSend.index', $dataView);
    }


    public function update(Request $request)
    {
        $data = $request->except('_token');
        $product100 = $data['100'];
        $lucky100 = $data['lucky100'];
        if($product100 && $lucky100){
            GiftSend::where('giftCoin', '100')->update([
                'product' => $product100,
                'luckyNumber' => $lucky100,
            ]);
        }
        $product200 = $data['200'];
        $lucky200 = $data['lucky200'];
        if($product200 && $lucky200){
            GiftSend::where('giftCoin', '200')->update([
                'product' => $product200,
                'luckyNumber' => $lucky200,
            ]);
        }
        $product500 = $data['500'];
        $lucky500 = $data['lucky500'];
        if($product500 && $lucky500){
            GiftSend::where('giftCoin', '500')->update([
                'product' => $product500,
                'luckyNumber' => $lucky500,
            ]);
        }
        $product1000 = $data['1000'];
        $lucky1000 = $data['lucky1000'];
        if($product1000 && $lucky1000){
            GiftSend::where('giftCoin', '1000')->update([
                'product' => $product1000,
                'luckyNumber' => $lucky1000,
            ]);
        }
        $product2000 = $data['2000'];
        $lucky2000 = $data['lucky2000'];
        if($product2000 && $lucky2000){
            GiftSend::where('giftCoin', '2000')->update([
                'product' => $product2000,
                'luckyNumber' => $lucky2000,
            ]);
        }
        $product5000 = $data['5000'];
        $lucky5000 = $data['lucky5000'];
        if($product5000 && $lucky5000){
            GiftSend::where('giftCoin', '5000')->update([
                'product' => $product5000,
                'luckyNumber' => $lucky5000,
            ]);
        }
        $product10000 = $data['10000'];
        $lucky10000 = $data['lucky10000'];
        if($product10000 && $lucky10000){
            GiftSend::where('giftCoin', '10000')->update([
                'product' => $product10000,
                'luckyNumber' => $lucky10000,
            ]);
        }
        return redirect()->route('admin.giftSend.index')->with('success', ' The update was successful !!! ');
    }

    public function delete($id)
    {
        $blog = GiftCode::findOrFail($id);
        $flagCheck = $blog->delete();
        if ($flagCheck > 0) {
            return redirect()->back()->with('success', ' The update was successful !!! ');
        }
        return redirect()->back()->with('danger', ' The update was failed !!! ');
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
