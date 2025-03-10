<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RechargeEventConfig;
use App\Models\RechargeEventGift;
use Illuminate\Http\Request;
use DB;

class RechargeEventsController extends Controller
{
    private $milestoneAmounts = [
        50000, 100000, 200000, 300000, 400000, 500000, 600000, 700000, 800000, 900000,
        1000000, 2000000, 3000000, 4000000, 5000000, 6000000, 7000000, 8000000, 9000000, 10000000
    ];

    public function index()
    {
        // Lấy config và danh sách quà
        $config = RechargeEventConfig::firstOrCreate([]);
        
        // Lấy tất cả sản phẩm bao gồm cả sản phẩm bị ẩn
        $products = Product::all();
        
        // Lấy danh sách quà cho từng loại
        $firstRechargeGifts = RechargeEventGift::where('event_type', 'first_recharge')
            ->with('product')
            ->get();
            
        $milestoneGifts = RechargeEventGift::where('event_type', 'milestone')
            ->with('product')
            ->get()
            ->groupBy('milestone_amount');

        // Tạo danh sách milestones với các mốc đã định sẵn
        $milestones = [];
        foreach ($this->milestoneAmounts as $amount) {
            $milestones[] = (object)[
                'amount' => $amount,
                'gifts' => $milestoneGifts->get($amount, collect())
            ];
        }

        return view('admin.rechargeEvents.index', compact(
            'config', 
            'products', 
            'milestones',
            'firstRechargeGifts'
        ));
    }

    public function updateFirstRecharge(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Thêm validate cho các trường thời gian mới
            $request->validate([
                'first_recharge_status' => 'required|in:0,1',
                'first_recharge_start_time' => 'required|date',
                'first_recharge_end_time' => 'required|date|after:first_recharge_start_time',
            ], [
                'first_recharge_start_time.required' => 'Vui lòng chọn thời gian bắt đầu',
                'first_recharge_end_time.required' => 'Vui lòng chọn thời gian kết thúc',
                'first_recharge_end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
            ]);
    
            // Cập nhật config
            $config = RechargeEventConfig::first();
            $config->update([
                'first_recharge_status' => $request->first_recharge_status,
                'first_recharge_start_time' => $request->first_recharge_start_time,
                'first_recharge_end_time' => $request->first_recharge_end_time
            ]);
    
            // Xử lý quà tặng như cũ
            RechargeEventGift::where('event_type', 'first_recharge')->delete();
    
            if ($request->has('first_recharge_gifts')) {
                foreach ($request->first_recharge_gifts as $productId) {
                    if (!empty($productId)) {
                        RechargeEventGift::create([
                            'event_type' => 'first_recharge',
                            'product_id' => $productId
                        ]);
                    }
                }
            }
    
            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật cấu hình nạp lần đầu thành công');
    
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    public function updateMilestone(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Thêm validate cho các trường thời gian
            $request->validate([
                'milestone_status' => 'required|in:0,1',
                'milestone_start_time' => 'required|date',
                'milestone_end_time' => 'required|date|after:milestone_start_time',
            ], [
                'milestone_start_time.required' => 'Vui lòng chọn thời gian bắt đầu',
                'milestone_end_time.required' => 'Vui lòng chọn thời gian kết thúc',
                'milestone_end_time.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
            ]);
    
            // Cập nhật config
            $config = RechargeEventConfig::first();
            $config->update([
                'milestone_status' => $request->milestone_status,
                'milestone_start_time' => $request->milestone_start_time,
                'milestone_end_time' => $request->milestone_end_time
            ]);
    
            // Xử lý quà tặng như cũ
            RechargeEventGift::where('event_type', 'milestone')->delete();
    
            if ($request->has('milestone_gifts')) {
                foreach ($request->milestone_gifts as $amount => $products) {
                    foreach ($products as $productId) {
                        if (!empty($productId)) {
                            RechargeEventGift::create([
                                'event_type' => 'milestone',
                                'milestone_amount' => $amount,
                                'product_id' => $productId
                            ]);
                        }
                    }
                }
            }
    
            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật cấu hình nạp theo mốc thành công');
    
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
    
    public function updateGoldenHour(Request $request)
    {
        try {
            $request->validate([
                'golden_hour_status' => 'required|boolean',
                'golden_hour_start_time' => 'required|date_format:H:i',
                'golden_hour_end_time' => 'required|date_format:H:i',
                'golden_hour_multiplier' => 'required|numeric|min:1',
                'golden_hour_event_start' => 'required|date',
                'golden_hour_event_end' => 'required|date|after:golden_hour_event_start'
            ], [
                'golden_hour_event_start.required' => 'Vui lòng chọn thời gian bắt đầu sự kiện',
                'golden_hour_event_end.required' => 'Vui lòng chọn thời gian kết thúc sự kiện',
                'golden_hour_event_end.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu'
            ]);
    
            $config = RechargeEventConfig::first();
            $config->update([
                'golden_hour_status' => $request->golden_hour_status,
                'golden_hour_start_time' => $request->golden_hour_start_time,
                'golden_hour_end_time' => $request->golden_hour_end_time,
                'golden_hour_multiplier' => $request->golden_hour_multiplier,
                'golden_hour_event_start' => $request->golden_hour_event_start,
                'golden_hour_event_end' => $request->golden_hour_event_end
            ]);
    
            return redirect()->back()->with('success', 'Cập nhật cấu hình giờ vàng thành công');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

public function getConfig()
{
    try {
        $config = RechargeEventConfig::first();
        
        // Lấy danh sách quà tặng
        $firstRechargeGifts = RechargeEventGift::where('event_type', 'first_recharge')
            ->with('product')
            ->get()
            ->map(function($gift) {
                return [
                    'id' => $gift->product->id,
                    'name' => $gift->product->prd_name,
                    'description' => $gift->product->prd_description,
                    'image' => $gift->product->prd_thunbar
                ];
            });

        $milestoneGifts = RechargeEventGift::where('event_type', 'milestone')
            ->with('product')
            ->get()
            ->groupBy('milestone_amount')
            ->map(function($gifts) {
                return $gifts->map(function($gift) {
                    return [
                        'id' => $gift->product->id,
                        'name' => $gift->product->prd_name,
                        'description' => $gift->product->prd_description,
                        'image' => $gift->product->prd_thunbar
                    ];
                });
            });

        // Sửa đổi response để thêm thông tin về xu và tiền VND
        return response()->json([
            'success' => true,
            'data' => [
                'first_recharge_status' => (bool)$config->first_recharge_status,
                'first_recharge_start_time' => $config->first_recharge_start_time,
                'first_recharge_end_time' => $config->first_recharge_end_time,
                'first_recharge_gifts' => $firstRechargeGifts,
                'first_recharge_min_xu' => 10, // Tối thiểu 10 xu (10.000đ)
                
                'milestone_status' => (bool)$config->milestone_status,
                'milestone_start_time' => $config->milestone_start_time,
                'milestone_end_time' => $config->milestone_end_time,
                'milestone_gifts' => $milestoneGifts,
                
                'golden_hour_status' => (bool)$config->golden_hour_status,
                'golden_hour_start_time' => $config->golden_hour_start_time,
                'golden_hour_end_time' => $config->golden_hour_end_time,
                'golden_hour_event_start' => $config->golden_hour_event_start,
                'golden_hour_event_end' => $config->golden_hour_event_end,
                'golden_hour_multiplier' => (float)$config->golden_hour_multiplier,
                'is_golden_hour' => $this->isGoldenHour($config),
                'next_golden_hour' => $this->getNextGoldenHour($config),
                'conversion_rate' => 1000 // 1 xu = 1000 VND
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
}