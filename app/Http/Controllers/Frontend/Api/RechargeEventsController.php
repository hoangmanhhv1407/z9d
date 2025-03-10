<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RechargeEventConfig;
use App\Models\RechargeEventGift;
use App\Models\TransactionHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use DB;

class RechargeEventsController extends Controller
{
    public function getConfig()
    {
        try {
            $config = RechargeEventConfig::firstOrCreate([]);
            $now = Carbon::now();
    
                  // Kiểm tra thời gian từng sự kiện
        $firstRechargeActive = $config->first_recharge_status && 
        $now->between(
            Carbon::parse($config->first_recharge_start_time),
            Carbon::parse($config->first_recharge_end_time)
        );

    $milestoneActive = $config->milestone_status && 
        $now->between(
            Carbon::parse($config->milestone_start_time),
            Carbon::parse($config->milestone_end_time)
        );

    $goldenHourActive = $config->golden_hour_status && 
        $now->between(
            Carbon::parse($config->golden_hour_event_start),
            Carbon::parse($config->golden_hour_event_end)
        );
            // Lấy quà nạp lần đầu
            $firstRechargeGifts = RechargeEventGift::where('event_type', 'first_recharge')
                ->with('product')
                ->get()
                ->map(function($gift) {
                    return [
                        'id' => $gift->product->id,
                        'name' => $gift->product->prd_name,
                        'image' => Storage::url('products/' . $gift->product->prd_thunbar),
                        'description' => $gift->product->prd_description
                    ];
                });
    
            // Lấy quà theo mốc nạp
            $milestoneGifts = RechargeEventGift::where('event_type', 'milestone')
                ->with('product')
                ->get()
                ->groupBy('milestone_amount')
                ->map(function($gifts, $amount) {
                    return [
                        'amount' => (int)$amount,
                        'products' => $gifts->map(function($gift) {
                            return [
                                'id' => $gift->product->id,
                                'name' => $gift->product->prd_name,
                                'image' => Storage::url('products/' . $gift->product->prd_thunbar),
                                'description' => $gift->product->prd_description
                            ];
                        })
                    ];
                })
                ->values();
    
            return response()->json([
                'success' => true,
                'data' => [
                    'first_recharge_status' => (bool)$config->first_recharge_status,
                    'first_recharge_gifts' => $firstRechargeGifts,
                    'first_recharge_start_time' => $config->first_recharge_start_time,
                    'first_recharge_end_time' => $config->first_recharge_end_time,
                    'milestone_status' => (bool)$config->milestone_status,
                    'milestone_gifts' => $milestoneGifts,
                    'milestone_start_time' => $config->milestone_start_time,
                    'milestone_end_time' => $config->milestone_end_time,
                    'golden_hour_status' => (bool)$config->golden_hour_status,
                    'golden_hour_start_time' => $config->golden_hour_start_time,
                    'golden_hour_end_time' => $config->golden_hour_end_time,
                    'golden_hour_multiplier' => (float)$config->golden_hour_multiplier,
                    'is_golden_hour' => $this->isGoldenHour($config),
                    'next_golden_hour' => $this->getNextGoldenHour($config)
                ]
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Error in getConfig: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy cấu hình sự kiện'
            ], 500);
        }
    }

    public function getUserRechargeStatus()
{
    try {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $config = RechargeEventConfig::first();

        // Kiểm tra đã nhận quà nạp lần đầu chưa
        $hasClaimedFirstRecharge = DB::table('first_recharge_claims')
            ->where('user_id', $user->id)
            ->exists();

        // Kiểm tra điều kiện nạp lần đầu
        $firstRecharge = TransactionHistory::where('user_id', $user->id)
            ->where('type', 1)
            ->orderBy('created_at', 'asc')
            ->first();

        // Kiểm tra nạp theo mốc
        $milestoneStart = Carbon::parse($config->milestone_start_time);
        $milestoneEnd = Carbon::parse($config->milestone_end_time);

        // Lấy các mốc đã nhận trong thời gian sự kiện mốc (mốc lưu dưới dạng VND)
        $claimedMilestones = DB::table('milestone_claims')
            ->where('user_id', $user->id)
            ->whereBetween('claimed_at', [$milestoneStart, $milestoneEnd])
            ->pluck('milestone_amount')
            ->toArray();

        // Tính tổng nạp trong thời gian sự kiện mốc (lưu dưới dạng xu)
        $totalRechargeXu = TransactionHistory::where('user_id', $user->id)
            ->where('type', 1)
            ->whereBetween('created_at', [$milestoneStart, $milestoneEnd])
            ->sum('raw_coin'); // Thay vì sum('coin')

        return response()->json([
            'success' => true,
            'data' => [
                'has_first_recharge' => $firstRecharge && $firstRecharge->coin >= 10, // 10 xu = 10.000 VND
                'has_claimed_first_recharge' => $hasClaimedFirstRecharge,
                'first_recharge_amount' => $firstRecharge ? $firstRecharge->coin : 0, // Đã là xu
                'total_recharge' => $totalRechargeXu, // Đã là xu
                'claimed_milestones' => $claimedMilestones, // Vẫn là VND
                'event_time' => [
                    'milestone' => [
                        'start' => $config->milestone_start_time,
                        'end' => $config->milestone_end_time
                    ]
                ]
            ]
        ]);

    } catch (\Exception $e) {
        Log::error('Error in getUserRechargeStatus: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi kiểm tra trạng thái nạp'
        ], 500);
    }
}

 
    private function isGoldenHour($config)
    {
        if (!$config->golden_hour_status) {
            return false;
        }

        $now = Carbon::now();
        $startTime = Carbon::createFromTimeString($config->golden_hour_start_time);
        $endTime = Carbon::createFromTimeString($config->golden_hour_end_time);

        return $now->between($startTime, $endTime);
    }

    private function getNextGoldenHour($config)
    {
        if (!$config->golden_hour_status) {
            return null;
        }

        $now = Carbon::now();
        $startTime = Carbon::createFromTimeString($config->golden_hour_start_time);

        if ($now->lt($startTime)) {
            return $startTime;
        }

        return $startTime->addDay();
    }
}