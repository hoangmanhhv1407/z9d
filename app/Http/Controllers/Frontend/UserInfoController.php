<?php namespace App\Http\Controllers\Frontend;

use Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Models\NDV01CharacState;
use App\Models\NDV01Charac;
use App\Models\Tbl_Cash_Inven;
use App\Models\GameConnect;
use App\Models\MemLog;
use App\Models\TimeSetting;
use App\Models\TransactionHistory;
use App\Models\GiftSendHistory;
use App\Models\Product; // Nhập model Product
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB; // Thêm dòng này
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserInfoController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guard('web')->check()) {
            $user = User::find(Auth::guard('web')->user()->id);
            $userData = $this->getUserData($user);
            
            // Kiểm tra xem người dùng đã nhận quà hôm nay chưa
            $hasClaimedGiftToday = $this->hasClaimedGiftToday($user);

            // Lấy level VIP
            $vipLevel = $this->getVipLevel($user); 
            $vipInfo = $this->getVipInfo($user);
            $totalDepositedCoins = $this->getTotalDepositedCoins($user->id);
            $totalSpentCoins = $this->getTotalSpentCoins($user->id);


            // Mã sản phẩm dựa trên level VIP
            $productCode = null;
            if ($vipLevel == 1) {
                $productCode = '2201034'; // Mã sản phẩm cho VIP 1
            } elseif ($vipLevel == 2) {
                $productCode = '2201035'; // Mã sản phẩm cho VIP 2
            } elseif ($vipLevel == 3) {
                $productCode = '2201036'; // Mã sản phẩm cho VIP 3
            } elseif ($vipLevel == 4) {
                $productCode = '2201037'; // Mã sản phẩm cho VIP 4
            } elseif ($vipLevel == 5) {
                $productCode = '2201038'; // Mã sản phẩm cho VIP 5
            } elseif ($vipLevel == 6) {
                $productCode = '2201039'; // Mã sản phẩm cho VIP 6
            } elseif ($vipLevel == 7) {
                $productCode = '2201040'; // Mã sản phẩm cho VIP 7
            } else {
                $productCode = '2201033'; // Mã sản phẩm mặc định cho người dùng không có VIP
            }

            // Lấy sản phẩm theo mã đã xác định
            $products = Product::with('categoryProduct')
                ->where('prd_code', $productCode)
                ->paginate(1); // Thay đổi số trang nếu cần
// Thêm trường already_claimed vào sản phẩm
foreach ($products as $product) {
    $product->already_claimed = GiftSendHistory::where('userId', $user->id)
        ->where('product_id', $product->id)
        ->whereDate('created_at', Carbon::today())
        ->exists();
}
            // Lấy sản phẩm số 2 nếu VIP level >= 1
            $vipProduct = null;
            if ($vipLevel >= 1) {
                $vipProduct = Product::with('categoryProduct')
                    ->where('prd_code', '2201033') // Mã sản phẩm số 2
                    ->first(); // Lấy sản phẩm đầu tiên
            }
// Kiểm tra trạng thái của sản phẩm VIP
if ($vipProduct) {
    $vipProduct->already_claimed = GiftSendHistory::where('userId', $user->id)
        ->where('product_id', $vipProduct->id)
        ->whereDate('created_at', Carbon::today())
        ->exists();
}            

            // Lấy mã sản phẩm từ danh sách sản phẩm
            $productCodes = $products->map(function ($product) {
                return $product->prd_code;
            });

            // Kiểm tra và xử lý nhận quà nếu có request từ người dùng
            if ($request->isMethod('post')) {
                // Xác thực dữ liệu từ request
                $request->validate([
                    'character_id' => 'required|string', // Kiểm tra id nhân vật
                    'gift_code' => 'required|string', // Kiểm tra mã quà
                ]);

                $characterId = $request->character_id;
                $giftCode = $request->gift_code;

                // Thêm vào bảng GiftSendHistory
                GiftSendHistory::insert([
                    'product_id' => Product::where('prd_code', $giftCode)->first()->id, // Lấy ID sản phẩm từ mã quà
                    'giftCoin' => 0,
                    'luckyNumber' => 0, // Có thể thêm số ngẫu nhiên nếu cần
                    'userId' => $user->id,
                    'status' => 1, // Trạng thái quà đã gửi
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                // Thêm vào bảng Tbl_Cash_Inven
                Tbl_Cash_Inven::insert([
                    'order_idx' => Tbl_Cash_Inven::max('order_idx') + 1,
                    'item_code' => $giftCode, // Sử dụng mã quà đã nhận
                    'item_user_id' => $user->userid,
                    'item_server_code' => 0,
                    'item_present' => 0,
                    'order_input_date' => Carbon::now(),
                ]);

                return response([
                    'success' => true,
                    'message' => 'Quà đã được nhận thành công!'
                ], Response::HTTP_OK);
            }

            $dataView = [
                'showHistory' => TransactionHistory::with('product')
                    ->where('user_id', $user->id)
                    ->with('userAdmin')
                    ->orderBy('id', 'desc')
                    ->paginate(10),
                'user' => $user,
                'chars' => $userData['chars'],
                'per_week' => $userData['per_week'],
                'min_level' => $userData['min_level'],
                'week_cha' => $userData['week_cha'],
                'accumulate' => $userData['accumulate'],
                'products' => $products, // Thêm danh sách sản phẩm vào dữ liệu hiển thị
                'vipProduct' => $vipProduct, // Thêm sản phẩm số 2 vào dữ liệu hiển thị
                'productCodes' => $productCodes, // Thêm mã sản phẩm vào dữ liệu hiển thị
                'vipLevel' => $vipLevel, // Thêm level VIP vào dữ liệu hiển thị
                'hasClaimedGiftToday' => $hasClaimedGiftToday, // Thêm trạng thái đã nhận quà hôm nay vào dữ liệu hiển thị
                'vipInfo' => $vipInfo, // Thêm dòng này
                'totalDepositedCoins' => $totalDepositedCoins,
                'totalSpentCoins' => $totalSpentCoins,
            ];

            if ($user->char_reg == null) {
                return view('frontend.info', $dataView);
            } else {
                $char = null;
                if (is_array($userData['chars'])) {
                    $char = current(array_filter($userData['chars'], function ($item) use ($user) {
                        return $item['unique_id'] == $user->char_reg;
                    }));
                } else if (isset($userData['chars'][0]) && $userData['chars'][0]->unique_id == $user->char_reg) {
                    $char = $userData['chars'][0];
                }

                if ($char) {
                    $dataView['char'] = $char;
                } else {
                    return response(['error' => 'bad_request'], Response::HTTP_BAD_REQUEST);
                }

                return view('frontend.info', $dataView);
            }
        }

        return redirect()->route('frontend.index');
    }

    public function hasClaimedGiftToday($user)
    {
        // Kiểm tra xem người dùng đã nhận quà trong ngày hôm nay
        return GiftSendHistory::where('userId', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->exists(); // Trả về true nếu đã nhận quà, false nếu chưa
    }

    public function getUserData($user)
    {
        $per_week = TimeSetting::find(2)->per_week;
        $minLevel = TimeSetting::where('id', 2)->first()->min_level;

        // Tính điểm tích lũy
        $max_char = $per_week - $user->week_cha;
        $accumulate = $user->accumulate ?? 0;

        // Lấy thông tin nhân vật
        $chars = (new NDV01Charac())->getCurrentCharactersInforWithQuery([
            ['ND_V01_Charac.user_id', '=', $user->userid], 
            ['ND_V01_CharacState.inner_level', '>', $minLevel]
        ]);

        return [
            'chars' => $chars,
            'per_week' => $max_char,
            'min_level' => $minLevel,
            'week_cha' => $max_char,
            'accumulate' => $accumulate,
        ];
    }

    public function getVipLevel($user)
    {
        // Lấy tổng số tiền đã nạp của người dùng
        $totalDeposits = 0;
        $transactionHistories = $user->transactionHistory()->whereIn('type', [1, 3])->get();
        foreach ($transactionHistories as $item) {
            $totalDeposits += $item->coin;
        }

    // Tính level VIP dựa vào tổng số tiền nạp
    if ($totalDeposits >= 10000) {
        return 7; // VIP 7
    } elseif ($totalDeposits >= 5000) {
        return 6; // VIP 6
    } elseif ($totalDeposits >= 4000) {
        return 5; // VIP 5
    } elseif ($totalDeposits >= 3000) {
        return 4; // VIP 4
    } elseif ($totalDeposits >= 2000) {
        return 3; // VIP 3
    } elseif ($totalDeposits >= 1000) {
        return 2; // VIP 2
    } elseif ($totalDeposits >= 500) {
        return 1; // VIP 1
    }

        return 0; // Không có level VIP
    }


    public function getVipInfo($user)
    {
        $totalDeposits = $user->transactionHistory()->whereIn('type', [1, 3])->sum('coin');
        $currentVip = $this->getVipLevel($user);
        $nextVip = min($currentVip + 1, 7); // Giới hạn VIP tối đa là 7
        
        $vipThresholds = [
            0 => 0,
            1 => 500,
            2 => 1000,
            3 => 2000,
            4 => 3000,
            5 => 4000,
            6 => 5000,
            7 => 10000
        ];
    
        $xuForNextVip = $vipThresholds[$nextVip];
        $xuNeeded = max(0, $xuForNextVip - $totalDeposits);
        
        if ($currentVip < 7) {
            $progress = min(100, ($totalDeposits / $xuForNextVip) * 100);
        } else {
            $progress = 100;
        }
    
        return [
            'currentVip' => $currentVip,
            'nextVip' => $nextVip,
            'totalDeposits' => $totalDeposits,
            'xuForNextVip' => $xuForNextVip,
            'xuNeeded' => $xuNeeded,
            'progress' => $progress,
            'totalCoins' => $user->coin
        ];
    }

    public function getVipInfoAjax()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $vipInfo = $this->getVipInfo($user);
        return response()->json([
            'success' => true,
            'currentVip' => $vipInfo['currentVip'],
            'nextVip' => $vipInfo['nextVip'],
            'totalDeposits' => $vipInfo['totalDeposits'],
            'xuForNextVip' => $vipInfo['xuForNextVip'],
            'xuNeeded' => $vipInfo['xuNeeded'],
            'progress' => $vipInfo['progress'],
            'totalCoins' => $vipInfo['totalCoins']
        ]);
    }

    private function getTotalDepositedCoins($userId)
{
    return TransactionHistory::where('user_id', $userId)
        ->whereIn('type', [1, 3]) // 1: Nạp MoMo, 3: Admin nạp
        ->sum('coin');
}

private function getTotalSpentCoins($userId)
{
    return TransactionHistory::where('user_id', $userId)
        ->where('type', 2) // 2: Mua vật phẩm
        ->sum('coin');
}


public function claimGift(Request $request)
{
    try {
        // Kiểm tra authentication
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'Unauthorized'
            ], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'character_id' => 'required|string',
            'gift_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'error' => $validator->errors()
            ], 400);
        }

        // Kiểm tra sản phẩm tồn tại
        $product = Product::where('prd_code', $request->gift_code)->first();
        if (!$product) {
            return response()->json([
                'success' => false,
                'error' => 'Sản phẩm không tồn tại'
            ], 404);
        }

        // Kiểm tra xem người chơi đã nhận quà nào trong ngày hôm nay chưa
        $giftReceivedToday = GiftSendHistory::where('userId', $user->id)
            ->whereDate('created_at', Carbon::today())
            ->pluck('product_id');

        // Kiểm tra đã nhận quà hôm nay chưa
        if ($giftReceivedToday->contains($product->id)) {
            return response()->json([
                'success' => false,
                'error' => 'Bạn đã nhận quà này trong ngày hôm nay'
            ], 400);
        }

        // Sử dụng transaction để đảm bảo tính nhất quán của dữ liệu
        DB::beginTransaction();
        try {
            // Thêm vào GiftSendHistory sử dụng insert thay vì create
            GiftSendHistory::insert([
                'product_id' => $product->id,
                'giftCoin' => 0,
                'luckyNumber' => 0,
                'userId' => $user->id,
                'status' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            // Thêm vào Tbl_Cash_Inven
            Tbl_Cash_Inven::insert([
                'order_idx' => Tbl_Cash_Inven::max('order_idx') + 1,
                'item_code' => $request->gift_code,
                'item_user_id' => $user->userid,
                'item_server_code' => 0,
                'item_present' => 0,
                'order_input_date' => Carbon::now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Quà đã được nhận thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

    } catch (\Exception $e) {
        \Log::error('Claim gift error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
        return response()->json([
            'success' => false,
            'error' => 'Có lỗi xảy ra khi nhận quà: ' . $e->getMessage()
        ], 500);
    }
}




    public function setCharacter(Request $request)
    {
        $user = User::find(Auth::guard('web')->user()->id);
        if ($user->char_reg) {
            return response(['error' => 'Bạn đã đăng ký nhân vật cho tài khoản này rồi'], 402);
        }
        $user->char_reg = $request->unique_id;
        $user->save();
        return response(['success' => 'Đổi nhân vật thành công'], 201);
    }

    public function getDailyGifts()
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $vipLevel = $this->getVipLevel($user);
    
    // Lấy sản phẩm dựa trên level VIP
    $productCode = '2201033'; // Mã mặc định cho non-VIP
    if ($vipLevel >= 1) {
        $productCode = '2201034'; // Cập nhật mã dựa trên level VIP
    }
    
    // Lấy danh sách quà
    $products = Product::whereIn('prd_code', ['2201033', $productCode])
        ->with('categoryProduct')
        ->get()
        ->map(function ($product) use ($user) {
            $product->already_claimed = GiftSendHistory::where('userId', $user->id)
                ->where('product_id', $product->id)
                ->whereDate('created_at', Carbon::today())
                ->exists();
            return $product;
        });

    return response()->json($products);
}

public function getUserCharacters()
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $minLevel = TimeSetting::where('id', 2)->first()->min_level;
    $chars = (new NDV01Charac())->getCurrentCharactersInforWithQuery([
        ['ND_V01_Charac.user_id', '=', $user->userid],
        ['ND_V01_CharacState.inner_level', '>', $minLevel]
    ]);

    return response()->json($chars);
}


public function getTransactionHistory()
{
    try {
        $user = Auth::guard('api')->user();
        
        $transactions = TransactionHistory::with(['product', 'userAdmin'])
            ->where('user_id', $user->id)
            ->select('id', 'product_id', 'user_id', 'coin', 'qty', 'type', 'created_at', 'code', 'phone')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'productName' => $transaction->product ? $transaction->product->prd_name : null,
                    'qty' => $transaction->qty, // Đảm bảo trả về qty
                    'coin' => $transaction->coin,
                    'transactionCode' => $transaction->code,
                    'phone' => $transaction->phone,
                    'type' => $transaction->type,
                    'date' => $transaction->created_at
                ];
            });

        return response()->json($transactions);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Có lỗi xảy ra khi lấy lịch sử giao dịch'
        ], 500);
    }
}

}
