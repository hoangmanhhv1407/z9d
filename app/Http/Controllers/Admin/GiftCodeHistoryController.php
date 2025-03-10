<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use App\Models\CategoryProduct;
use App\Models\GiftCode;
use App\Models\GiftCodeHistory;
use App\Models\NameGiftCode;
use App\Models\Product;
use App\Models\TransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GiftCodeHistoryController extends Controller
{
    public function index(Request $request)
    {
        if($request->name){
            $giftCode = NameGiftCode::where('name',$request->name)->value('id');
            $showHistory = GiftCodeHistory::where('gift_code_name_id',$giftCode)->with(['giftCodeName' => function ($query){
                $query->with('giftCode');
            }])->with('user')->orderBy('id','desc')->paginate(10);
        }else{
            $showHistory = GiftCodeHistory::with(['giftCodeName' => function ($query){
                $query->with('giftCode');
            }])->with('user')->orderBy('id','desc')->paginate(10);

        }
//dd($showHistory);
        $dataView = [
            'showHistory' => $showHistory,
            'query' => $request->query()
        ];
        return view('admin.giftCodeHistory.index', $dataView);
    }

}
