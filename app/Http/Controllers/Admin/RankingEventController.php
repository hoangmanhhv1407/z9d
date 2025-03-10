<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RankingEventConfig;
use App\Models\RankingReward;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class RankingEventController extends Controller
{
public function index()
{
    // Lấy hoặc tạo config nếu chưa có
    $config = RankingEventConfig::firstOrCreate([]);
    
    // Lấy danh sách tất cả sản phẩm (kể cả bị ẩn)
    $products = Product::all();
    
    // Lấy config phần thưởng đã cấu hình
    $rewards = [];
    $rankingTypes = [
        'recharge', 'spend', 'level', 'honor', 'gong'
    ];
    
    foreach($rankingTypes as $type) {
        $rewards[$type] = $this->getCurrentRewards($type);
    }

    return view('admin.rankingEvent.index', compact('config', 'products', 'rewards'));
}

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            // Validate input
            $request->validate([
                'event_status' => 'required|in:0,1',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
            ]);

            // Cập nhật config chung
            $config = RankingEventConfig::first();
            $config->update([
                'status' => $request->event_status,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time
            ]);

            // Xóa config phần thưởng cũ
            RankingReward::truncate();

            // Cập nhật phần thưởng mới
            if(isset($request->rewards)) {
                foreach($request->rewards as $type => $typeRewards) {
                    foreach($typeRewards as $rank => $productId) {
                        if(!empty($productId)) {
                            // Xác định rank_to dựa vào rank_from
                            $rankTo = $rank == 1 ? 1 : ($rank == 2 ? 3 : 10);
                            
                            RankingReward::create([
                                'ranking_type' => $type,
                                'rank_from' => $rank,
                                'rank_to' => $rankTo,
                                'product_id' => $productId
                            ]);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Cập nhật cấu hình thành công!');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Phương thức hỗ trợ phát quà khi event kết thúc
    protected function distributeRewards()
    {
        $config = RankingEventConfig::first();
        
        if(!$config->status || Carbon::now() < Carbon::parse($config->end_time)) {
            return;
        }

        // Logic phát quà cho từng loại ranking
        $rankingTypes = ['recharge', 'spend', 'level', 'honor', 'gong'];
        
        foreach($rankingTypes as $type) {
            $rewards = RankingReward::where('ranking_type', $type)->get();
            
            foreach($rewards as $reward) {
                // Lấy danh sách người chơi trong khoảng rank
                $players = $this->getPlayersByRank($type, $reward->rank_from, $reward->rank_to);
                
                foreach($players as $player) {
                    // Logic phát quà cho từng người chơi
                    $this->giveRewardToPlayer($player, $reward->product_id);
                }
            }
        }

        // Đánh dấu event đã kết thúc
        $config->status = 0;
        $config->save();
    }
    public function updateRewards(Request $request, $type)
    {
        try {
            DB::beginTransaction();
            
            // Validate request
            $request->validate([
                'status' => 'required|in:0,1',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'rewards' => 'array'
            ]);
    
            // Cập nhật config cho type
            $config = RankingEventConfig::first();
            $typeStatus = $config->type_status ?? [];
            $typeStartTime = $config->type_start_time ?? [];
            $typeEndTime = $config->type_end_time ?? [];
    
            $typeStatus[$type] = $request->status;
            $typeStartTime[$type] = $request->start_time;
            $typeEndTime[$type] = $request->end_time;
    
            $config->update([
                'type_status' => $typeStatus,
                'type_start_time' => $typeStartTime,
                'type_end_time' => $typeEndTime
            ]);
    
            // Xóa rewards cũ của type này
            RankingReward::where('ranking_type', $type)->delete();
    
            // Thêm rewards mới
            if ($request->has('rewards') && isset($request->rewards[$type])) {
                foreach ($request->rewards[$type] as $rank => $products) {
                    if (!empty($products)) {
                        foreach ($products as $productId) {
                            if (!empty($productId)) {
                                RankingReward::create([
                                    'ranking_type' => $type,
                                    'rank_from' => $rank,
                                    'rank_to' => $rank,
                                    'product_id' => $productId
                                ]);
                            }
                        }
                    }
                }
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Cập nhật cấu hình thành công!');
    
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Error in updateRewards: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    protected function getPlayersByRank($type, $rankFrom, $rankTo)
    {
        // Logic lấy danh sách người chơi theo rank và loại ranking
        // Cần implement dựa vào cấu trúc database của bạn
        return [];
    }

    protected function giveRewardToPlayer($player, $productId)
    {
        // Logic phát quà cho người chơi
        // Cần implement dựa vào cấu trúc database của bạn
    }
protected function getCurrentRewards($type)
{
    $rewards = RankingReward::where('ranking_type', $type)
        ->orderBy('rank_from')
        ->get();
        
    // Nhóm các phần thưởng theo rank_from
    $groupedRewards = [];
    foreach ($rewards as $reward) {
        $groupedRewards[$reward->rank_from][] = $reward;
    }
    
    return $groupedRewards;
}
    protected function validateProduct($productId)
    {
        // Kiểm tra sản phẩm tồn tại, không quan tâm trạng thái
        return Product::where('id', $productId)->exists();
    }
}