<?php namespace App\Http\Controllers\Admin;
use App\Http\Requests\ProductRequest;
use App\Models\CategoryProduct;
use App\Models\GiftCode;
use App\Models\GiftCodeHistory;
use App\Models\GiftSend;
use App\Models\GiftSendHistory;
use App\Models\LuckyHistory;
use App\Models\NameGiftCode;
use App\Models\Product;
use App\Models\Tbl_Cash_Inven;
use App\Models\TransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class GiftSendHistoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->name) {
            $name = $request->name;
            $user = User::where('userid', $name)->value('id');
            $showHistory = GiftSendHistory::with('user')
                ->where('userId', $user)
                ->with('product')
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            $showHistory = GiftSendHistory::with('user')
                ->with('product')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        self::installHistory();
        $dataView = ['showHistory' => $showHistory, 'query' => $request->query()];
        return view('admin.giftSendHistory.index', $dataView);
    }
    public function installHistory()
    {
        $listUserNap = User::with([
            'transactionHistory' => function ($query) {
                $query->whereIn('type', [1, 3]);
            },
        ])
            ->whereHas('transactionHistory')
            ->get();
        $listData = [];
        foreach ($listUserNap as $key => $value) {
            $total = 0;
            foreach ($value->transactionHistory as $item) {
                $total += $item->coin;
            }
            $listData[$key]['total'] = $total;
            $listData[$key]['userId'] = $value->id;
        }
        $collection = collect($listData)
            ->SortByDesc('total')
            ->values()
            ->all();
        foreach ($collection as $key => $value) {
            if ($value['total'] >= 500) {
                $product = GiftSend::where('giftCoin', 500)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 500)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 500, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 1000) {
                $product = GiftSend::where('giftCoin', 1000)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 1000)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 1000, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 2000) {
                $product = GiftSend::where('giftCoin', 2000)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 2000)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 2000, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 3000) {
                $product = GiftSend::where('giftCoin', 3000)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 3000)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 3000, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 4000) {
                $product = GiftSend::where('giftCoin', 4000)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 4000)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 4000, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 5000) {
                $product = GiftSend::where('giftCoin', 5000)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 5000)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 5000, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
            if ($value['total'] >= 10000) {
                $product = GiftSend::where('giftCoin', 10000)->first();
                $check = GiftSendHistory::where('userId', $value['userId'])
                    ->where('giftCoin', 10000)
                    ->first();
                if (!$check && $product && $product->product != null) {
                    $dataImport = ['product_id' => $product->product, 'luckyNumber' => $product->luckyNumber, 'giftCoin' => 10000, 'userId' => $value['userId'], 'status' => 3, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
                    GiftSendHistory::insert($dataImport);
                }
            }
        }
    }
    public function luckyHistory(Request $request)
    {
        if ($request->name) {
            print_r($request->name);
            die();
            $name = $request->name;
            $user = User::where('userid', $name)->value('id');
            $showHistory = LuckyHistory::with('user')
                ->where('userId', $user)
                ->with('product')
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            $showHistory = LuckyHistory::with('user')
                ->with('product')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }
        $dataView = ['showHistory' => $showHistory, 'query' => $request->query()];
        return view('admin.lucky.index', $dataView);
    }
    public function getUserVip(Request $request)
    {
        $startCoin = explode('-', $request->gift)[0];
        $endCoin = explode('-', $request->gift)[1];
        $listUserNap = User::with([
            'transactionHistory' => function ($query) {
                $query->whereIn('type', [1, 3]);
            },
        ])
            ->whereHas('transactionHistory')
            ->get();
        $listData = [];
        foreach ($listUserNap as $key => $value) {
            $total = 0;
            foreach ($value->transactionHistory as $item) {
                $total += $item->coin;
            }
            $listData[$key]['total'] = $total;
            $listData[$key]['userid'] = $value->userid;
            $listData[$key]['luckyNumber'] = $value->luckyNumber;
        }
        $resData = [];
        foreach ($listData as $key2 => $value2) {
            if ((int) $startCoin <= (int) $value2['total'] && (int) $value2['total'] <= (int) $endCoin) {
                array_push($resData, $value2);
            }
        }
        return response()->json(['data' => $resData]);
    }
    public function addLuckyIndex()
    {
        $user = User::get();
        return view('admin.giftSendHistory.addLuckyNumber', compact('user'));
    }
    public function postAddLuckyIndex(Request $request)
    {
        if ($request->user && $request->vip) {
            return redirect()
                ->back()
                ->with('danger', 'Chỉ được chọn cấp Vip hoặc tài khoản để thêm lượt quay !!! ');
        }
        if ($request->vip && $request->turn > 0) {
            $startCoin = explode('-', $request->vip)[0];
            $endCoin = explode('-', $request->vip)[1];
            $listUserNap = User::with([
                'transactionHistory' => function ($query) {
                    $query->whereIn('type', [1, 3]);
                },
            ])
                ->whereHas('transactionHistory')
                ->get();
            $listData = [];
            foreach ($listUserNap as $key => $value) {
                $total = 0;
                foreach ($value->transactionHistory as $item) {
                    $total += $item->coin;
                }
                $listData[$key]['total'] = $total;
                $listData[$key]['userid'] = $value->userid;
                $listData[$key]['id'] = $value->id;
                $listData[$key]['luckyNumber'] = $value->luckyNumber;
            }
            foreach ($listData as $key2 => $value2) {
                if ((int) $startCoin <= (int) $value2['total'] && (int) $value2['total'] <= (int) $endCoin) {
                    User::where('id', $value2['id'])->increment('luckyNumber', (int) $request->turn);
                }
            }
            return redirect()
                ->back()
                ->with('success', 'Thêm lượt quay thành công !!! ');
        }
        if ($request->user && $request->turn > 0) {
            User::where('id', $request->user)->increment('luckyNumber', (int) $request->turn);
            return redirect()
                ->back()
                ->with('success', 'Thêm lượt quay thành công !!! ');
        }
        return redirect()
            ->back()
            ->with('danger', 'Thêm lượt quay thất bại !!! ');
    }
    public function addGiftForUser()
    {
        $productList = Product::where('prd_status', 1)->get();
        $user = User::get();
        return view('admin.giftSendHistory.addGiftForUser', compact('user', 'productList'));
    }
    public function addAccuForUser()
    {
        $user = User::get();
        return view('admin.giftSendHistory.addAccuForUser', compact('user'));
    }
public function postAddGiftForUser(Request $request)
{
    if ($request->all === 'on') {
        // Lấy tất cả người dùng
        $users = User::get();

        foreach ($users as $user) {
            // Dữ liệu gửi quà
            $dataImport = [
                'product_id' => $request->gift,
                'giftCoin' => 0,
                'luckyNumber' => $request->turn,
                'userId' => $user->id,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];

            // Thêm vào bảng GiftSendHistory
            GiftSendHistory::insert($dataImport);

            // Lấy mã sản phẩm (prd_code) từ bảng Product
            $item_code = Product::where('id', $request->gift)->first()->prd_code;

            // Thêm dữ liệu vào bảng Tbl_Cash_Inven
            Tbl_Cash_Inven::insert([
                'order_idx' => Tbl_Cash_Inven::max('order_idx') + 1,
                'item_code' => $item_code,
                'item_user_id' => $user->userid, // userid của người chơi
                'item_server_code' => 0,
                'item_present' => 0,
                'order_input_date' => Carbon::now(),
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Gửi quà và cập nhật vào database thành công cho tất cả người chơi !!!');
    } else {
        // Gửi quà cho một người chơi cụ thể
        $dataImport = [
            'product_id' => $request->gift,
            'giftCoin' => 0,
            'luckyNumber' => $request->turn,
            'userId' => $request->user,
            'status' => 3,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        // Thêm vào bảng GiftSendHistory
        GiftSendHistory::insert($dataImport);

        // Lấy mã sản phẩm (prd_code) từ bảng Product
        $item_code = Product::where('id', $request->gift)->first()->prd_code;

        // Thêm dữ liệu vào bảng Tbl_Cash_Inven
        Tbl_Cash_Inven::insert([
            'order_idx' => Tbl_Cash_Inven::max('order_idx') + 1,
            'item_code' => $item_code,
            'item_user_id' => User::where('id', $request->user)->first()->userid, // userid của người chơi
            'item_server_code' => 0,
            'item_present' => 0,
            'order_input_date' => Carbon::now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Gửi quà và cập nhật vào database thành công !!!');
    }

    return redirect()
        ->back()
        ->with('danger', 'Gửi quà thất bại !!!');
}

    public function postAddAccuForUser(Request $request)
    {
        $user = User::findOrFail($request->user);
        if ($user) {
            $user->accumulate += $request->accumulate;
            $user->save();
            return redirect()
                ->back()
                ->with('success', 'Cộng điểm tích luỹ thành công !!! ');
        }
        return redirect()
            ->back()
            ->with('danger', 'Cộng điểm tích luỹ thất bại !!! ');
    }
  public function addGiftVip()
{
    // Lấy danh sách sản phẩm
    $productList = Product::where('prd_status', 1)->get();

    // Cài đặt lịch sử, giữ nguyên
    self::installHistory();

    // Truyền productList vào view
    return view('admin.giftSendHistory.addGiftVip', compact('productList'));
}
public function postAddGiftVip(Request $request)
{
    if ($request->vip) {
        $startCoin = explode('-', $request->vip)[0];
        $endCoin = explode('-', $request->vip)[1];
        
        // Lấy tất cả người dùng và lịch sử giao dịch của họ
        $listUserNap = User::with([
            'transactionHistory' => function ($query) {
                $query->whereIn('type', [1, 3]);
            },
        ])
            ->whereHas('transactionHistory')
            ->get();
        
        $listData = [];
        foreach ($listUserNap as $value) {
            $total = $value->transactionHistory->sum('coin');
            if ((int)$startCoin <= (int)$total && (int)$total <= (int)$endCoin) {
                $listData[] = [
                    'total' => $total,
                    'userid' => $value->userid,
                    'id' => $value->id,
                    'luckyNumber' => $value->luckyNumber,
                ];
            }
        }
        
        // Gửi quà cho tất cả người dùng đã lọc
        foreach ($listData as $userData) {
            $dataImport = [
                'product_id' => $request->gift, // ID sản phẩm sẽ được gửi
                'giftCoin' => 0,
                'luckyNumber' => $request->turn,
                'userId' => $userData['id'],
                'status' => 3, // Giả sử trạng thái 1 có nghĩa là đã gửi
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            GiftSendHistory::create($dataImport);
        }
        
        return redirect()
            ->back()
            ->with('success', 'Gửi quà thành công cho VIP !!! ');
    }
    return redirect()
        ->back()
        ->with('danger', 'Gửi quà thất bại !!! ');
}



    public function getUserVip2(Request $request)
    {
        $startCoin = explode('-', $request->gift)[0];
        $endCoin = explode('-', $request->gift)[1];
        $listUserNap = User::with([
            'transactionHistory' => function ($query) {
                $query->whereIn('type', [1, 3]);
            },
        ])
            ->whereHas('transactionHistory')
            ->get();
        $listData = [];
        foreach ($listUserNap as $key => $value) {
            $total = 0;
            foreach ($value->transactionHistory as $item) {
                $total += $item->coin;
            }
            $listData[$key]['total'] = $total;
            $listData[$key]['userid'] = $value->userid;
            $listData[$key]['id'] = $value->id;
            $listData[$key]['luckyNumber'] = $value->luckyNumber;
        }
        $resData = [];
        foreach ($listData as $key2 => $value2) {
            if ((int) $startCoin <= (int) $value2['total'] && (int) $value2['total'] <= (int) $endCoin) {
                $check = GiftSendHistory::where('userId', $value2['id'])->first();
                if ($check) {
                    array_push($resData, $value2);
                }
            }
        }
        return response()->json(['data' => $resData]);
    }
    public function send($id, $status, $luckyNumber = 0)
    {
        if ($status == 1) {
            $check = GiftSendHistory::where('id', $id)
                ->with('user')
                ->with('product')
                ->first();
            $item_code = Product::where('id', $check->product_id)->first()->prd_code;
            Tbl_Cash_Inven::insert(['order_idx' => Tbl_Cash_Inven::max('order_idx') + 1, 'item_code' => $item_code, 'item_user_id' => $check->user->userid, 'item_server_code' => 0, 'item_present' => 0, 'order_input_date' => Carbon::now()]);
        }
        GiftSendHistory::where('id', $id)->update(['status' => $status]);
        return redirect()
            ->back()
            ->with('success', ' The update was successful !!! ');
    }
}
