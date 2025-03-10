<?php namespace App\Http\Controllers\Admin;
use App\Http\Requests\ProductRequest;
use App\Models\CategoryProduct;
use App\Models\GiftSend;
use App\Models\GiftSendHistory;
use App\Models\LuckyNumberSendHistory;
use App\Models\Product;
use App\Models\TimeSetting;
use App\Models\TransactionHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $showHistory = TransactionHistory::with('product');
        $total = TransactionHistory::orderBy('id', 'desc');
        if ($request->type) {
            $showHistory = $showHistory->where('type', $request->type);
            $total = $total->where('type', $request->type);
        }
        if ($request->name) {
            $user = User::where('userid', $request->name)->value('id');
            $showHistory = $showHistory->where('user_id', $user);
            $total = $total->where('user_id', $user);
        }
        $total = $total->sum('coin');
        $showHistory = $showHistory
            ->with('user')
            ->with('userAdmin')
            ->orderBy('id', 'desc')
            ->paginate(10);
        $dataView = ['showHistory' => $showHistory, 'total' => $total, 'query' => $request->query()];
        return view('admin.transactionHistory.index', $dataView);
    }
    public function add()
    {
        $user = User::get();
        return view('admin.transactionHistory.add', compact('user'));
    }
public function store(Request $request)
{
    $coin = $request->coin;
    $totalCoin = (int) $coin;  // Giữ nguyên số lượng xu, không nhân hệ số
    $totalCoin2 = (int) $coin; // Giữ nguyên số lượng xu cho accumulate
    
    // Chỉ cập nhật điểm event nếu sự kiện đang diễn ra
    $timeSetting3 = TimeSetting::where('id', 3)->where('status', 1)->first();
    
    if (isset($timeSetting3)) {
        $now = strtotime(Carbon::now());
        $start = strtotime($timeSetting3->day_start);
        $end = strtotime($timeSetting3->day_end);
        if($now > $end) {
            $timeSetting3->status = 0;
            $timeSetting3->save();
        }
        if($now > $start && $now < $end) {
            User::where('id', $request->user)->increment('char_event', (int) $coin);
        }
    }
    
    $dataImport1 = [
        'user_id' => $request->user,
        'coin' => $totalCoin,
        'type' => 3,  // Type 3 là admin thêm xu
        'admin_id' => Auth::guard('web')->user()->id,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
        'accumulate' => $totalCoin2,
        'raw_coin' => $coin  // Lưu giá trị gốc nếu cần thiết
    ];
    
    TransactionHistory::insert($dataImport1);
    User::where('id', $request->user)->increment('coin', (int) $totalCoin);
    
    return redirect()
        ->route('admin.transactionHistory.index')
        ->with('success', ' Thêm coin thành công !!! ');
}
    public function edit()
    {
        $user = User::get();
        return view('admin.transactionHistory.edit', compact('user'));
    }
    public function storeMinus(Request $request)
    {
        $dataImport = ['user_id' => $request->user, 'coin' => $request->coin, 'type' => 4, 'admin_id' => Auth::guard('web')->user()->id, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()];
        $id = TransactionHistory::insert($dataImport);
        $user = User::where('id', $request->user)->value('coin');
        if ((int) $request->coin > (int) $user) {
            return redirect()
                ->back()
                ->with(['alert' => 'warning', 'message' => 'Tài khoản không đủ coin']);
        }
        User::where('id', $request->user)->decrement('coin', (int) $request->coin);
        if ($id > 0) {
            return redirect()
                ->route('admin.transactionHistory.index')
                ->with('success', ' Trừ coin thành công !!! ');
        }
        return redirect()
            ->route('admin.transactionHistory.index')
            ->with('danger', ' Trừ coin thất bại !!! ');
    }

    public function addCoinForUser($userId, $amount)
    {
        $coin = $amount;
        $totalCoin = (int) $coin;
        $totalCoin2 = $totalCoin;
    
        $timeSetting = TimeSetting::where('id', 1)->where('status', 1)->first();
        $timeSetting2 = TimeSetting::where('id', 2)->where('status', 1)->first();
        $timeSetting3 = TimeSetting::where('id', 3)->where('status', 1)->first();
    
        if (isset($timeSetting)) {
            $totalCoin = (int) ($coin * $timeSetting->number);
        }
    
        if (isset($timeSetting2)) {
            $totalCoin2 = (int) ($coin * $timeSetting2->number);
            User::where('id', $userId)->increment('accumulate', (int) $totalCoin2);
        }
    
        if (isset($timeSetting3)) {
            $now = strtotime(Carbon::now());
            $start = strtotime($timeSetting3->day_start);
            $end = strtotime($timeSetting3->day_end);
            if($now > $end) {
                $timeSetting3->status = 0;
                $timeSetting3->save();
            }
            if($now > $start && $now < $end) {
                User::where('id', $userId)->increment('char_event', (int) $coin);
            }
        }
    
        $dataImport1 = [
            'user_id' => $userId,
            'coin' => $totalCoin,
            'type' => 3,
            'admin_id' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'accumulate' => $totalCoin2
        ];
    
        TransactionHistory::insert($dataImport1);
        User::where('id', $userId)->increment('coin', (int) $totalCoin);
    
        return true;
    }
}
