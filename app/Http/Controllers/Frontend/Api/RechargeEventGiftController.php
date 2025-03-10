<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\RechargeEventConfig;
use App\Models\RechargeEventGift;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RechargeEventGiftController extends Controller
{
    private function validateClaimRequest($type)
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Vui lòng đăng nhập để nhận quà'
            ];
        }
    
        $config = RechargeEventConfig::first();
        $now = Carbon::now();
    
        switch ($type) {
            case 'first_recharge':
                if (!$config->first_recharge_status || 
                    !$now->between(
                        Carbon::parse($config->first_recharge_start_time),
                        Carbon::parse($config->first_recharge_end_time)
                    )) {
                    return [
                        'success' => false,
                        'message' => 'Sự kiện nạp lần đầu chưa diễn ra hoặc đã kết thúc'
                    ];
                }
                break;
                
            case 'milestone':
                if (!$config->milestone_status || 
                    !$now->between(
                        Carbon::parse($config->milestone_start_time),
                        Carbon::parse($config->milestone_end_time)
                    )) {
                    return [
                        'success' => false,
                        'message' => 'Sự kiện nạp theo mốc chưa diễn ra hoặc đã kết thúc'
                    ];
                }
                break;
        }
    
        return ['success' => true];
    }

private function checkFirstRechargeCondition($userId) 
{
    // Tính tổng số tiền nạp đầu tiên - giờ là xu (đã chia 1000)
    $totalFirstRecharge = TransactionHistory::where('user_id', $userId)
        ->where('type', 1) // Type 1 là nạp tiền
        ->orderBy('created_at', 'asc') // Lấy giao dịch đầu tiên
        ->limit(1)  // Chỉ lấy giao dịch đầu tiên
        ->value('raw_coin'); // Thay vì sum('coin')

    if ($totalFirstRecharge < 10) { // 10 xu = 10.000 VND
        return [
            'status' => false,
            'message' => 'Số tiền nạp lần đầu phải từ 10 xu trở lên (10.000đ)'
        ];
    }

    return [
        'status' => true
    ];
}
    
public function claimFirstRechargeGift()
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để nhận quà'
            ], 401);
        }

        DB::beginTransaction();
        try {
            // Kiểm tra đã nhận quà chưa 
            $hasClaimedGift = DB::table('first_recharge_claims')
                ->where('user_id', $user->id)
                ->exists();

            if ($hasClaimedGift) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã nhận quà nạp lần đầu rồi'
                ], 400);
            }

            // THAY ĐỔI: Kiểm tra điều kiện nạp - đang lưu dưới dạng xu
            // Nên 10 xu = 10.000 VND
            $totalFirstRecharge = TransactionHistory::where('user_id', $user->id)
                ->where('type', 1)
                ->orderBy('created_at', 'asc')
                ->value('coin');

            if (!$totalFirstRecharge || $totalFirstRecharge < 10) { // Kiểm tra 10 xu thay vì 10.000 VND
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa đủ điều kiện nhận quà nạp lần đầu (tối thiểu 10 xu)'
                ], 400);
            }

            // Lấy danh sách quà
            $gifts = RechargeEventGift::where('event_type', 'first_recharge')
                ->with('product')
                ->get();

            if ($gifts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quà tặng được cấu hình'
                ], 404);
            }

            // Thêm vào inventory
            foreach ($gifts as $gift) {
                $maxOrderIdx = DB::connection('cuuamsql')
                    ->table('Tbl_Cash_Inven')
                    ->max('order_idx') ?? 0;

                DB::connection('cuuamsql')->table('Tbl_Cash_Inven')->insert([
                    'order_idx' => $maxOrderIdx + 1,
                    'item_code' => $gift->product->prd_code,
                    'item_user_id' => $user->userid,
                    'item_server_code' => 0,
                    'item_present' => 0,
                    'order_input_date' => Carbon::now()
                ]);
            }

            // Lưu lịch sử nhận quà
            DB::table('first_recharge_claims')->insert([
                'user_id' => $user->id,
                'claimed_at' => Carbon::now()
            ]);

            // Lưu log giao dịch
            foreach ($gifts as $gift) {
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'product_id' => $gift->product_id,
                    'type' => 3,
                    'qty' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'transaction_hash' => hash('sha256', "{$user->id}_{$gift->product_id}_first_recharge_{time()}")
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nhận quà nạp lần đầu thành công',
                'data' => [
                    'gifts' => $gifts->map(function($gift) {
                        return [
                            'name' => $gift->product->prd_name,
                            'quantity' => 1
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    } catch (\Exception $e) {
        Log::error('Error in claimFirstRechargeGift: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi nhận quà: ' . $e->getMessage()
        ], 500);
    }
}

public function claimMilestoneGift(Request $request)
{
    try {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        DB::beginTransaction();
        try {
            $config = RechargeEventConfig::first();
            $now = Carbon::now();
            $eventStart = Carbon::parse($config->milestone_start_time);
            $eventEnd = Carbon::parse($config->milestone_end_time);
            
            // Lấy mốc VND từ request
            $amountVND = $request->amount;
            
            // Chuyển đổi sang xu để so sánh với database
            $amountXu = (int)($amountVND / 1000);

            // Kiểm tra thời gian sự kiện
            if (!$now->between($eventStart, $eventEnd)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ngoài thời gian nhận quà sự kiện'
                ], 400);
            }

            // Kiểm tra đã nhận quà của mốc này trong sự kiện hiện tại chưa
            $previousClaim = DB::table('milestone_claims')
                ->where('user_id', $user->id)
                ->where('milestone_amount', $amountVND)
                ->where('claimed_at', '>=', $eventStart)
                ->where('claimed_at', '<=', $eventEnd)
                ->first();

            if ($previousClaim) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã nhận quà của mốc này trong sự kiện này rồi'
                ], 400);
            }

            // Kiểm tra tổng nạp trong thời gian sự kiện hiện tại
            // Database lưu xu nên ta so sánh với xu
            $totalRechargeXu = TransactionHistory::where('user_id', $user->id)
                ->where('type', 1)
                ->whereBetween('created_at', [$eventStart, $eventEnd])
                ->sum('raw_coin'); // Thay vì sum('coin')

            if ($totalRechargeXu < $amountXu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn chưa đạt đủ mốc nạp trong thời gian sự kiện'
                ], 400);
            }

            // Lấy và kiểm tra quà tặng - vẫn dùng mốc VND để lấy quà
            $gifts = RechargeEventGift::where('event_type', 'milestone')
                ->where('milestone_amount', $amountVND)
                ->with('product')
                ->get();

            if ($gifts->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không có quà tặng cho mốc này'
                ], 404);
            }

            // Thêm vào inventory
            foreach ($gifts as $gift) {
                $maxOrderIdx = DB::connection('cuuamsql')
                    ->table('Tbl_Cash_Inven')
                    ->max('order_idx') ?? 0;

                DB::connection('cuuamsql')->table('Tbl_Cash_Inven')->insert([
                    'order_idx' => $maxOrderIdx + 1,
                    'item_code' => $gift->product->prd_code,
                    'item_user_id' => $user->userid,
                    'item_server_code' => 0,
                    'item_present' => 0,
                    'order_input_date' => Carbon::now()
                ]);
            }

            // Lưu lịch sử nhận quà với mốc VND
            DB::table('milestone_claims')->insert([
                'user_id' => $user->id,
                'milestone_amount' => $amountVND,
                'claimed_at' => Carbon::now()
            ]);

            // Lưu log giao dịch
            foreach ($gifts as $gift) {
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'product_id' => $gift->product_id,
                    'type' => 3,
                    'qty' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'transaction_hash' => hash('sha256', "{$user->id}_{$gift->product_id}_milestone_{$amountVND}_{time()}")
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nhận quà thành công',
                'data' => [
                    'gifts' => $gifts->map(function($gift) {
                        return [
                            'name' => $gift->product->prd_name,
                            'quantity' => 1
                        ];
                    })
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

    } catch (\Exception $e) {
        Log::error('Error in claimMilestoneGift: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi nhận quà'
        ], 500);
    }
}
}