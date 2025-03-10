<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\RankingEventConfig;
use App\Models\RankingReward;
use App\Models\TransactionHistory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\NDV01CharacState;
use App\Models\NDV02Elixir;

class RankingEventGiftController extends Controller
{

    // Thêm mapping đúng cho các loại ranking
    private $typeMapping = [
        'level' => 'level',
        'honor' => 'honor',
        'gong' => 'gong',
        'top-recharge' => 'recharge',
        'top-spend' => 'spend'
    ];

    private function validateClaimRequest($type)
    {
        $user = Auth::user();
        if (!$user) {
            return [
                'success' => false,
                'message' => 'Vui lòng đăng nhập để nhận quà'
            ];
        }

        $config = RankingEventConfig::first();
        if (!$config || !$config->status) {
            return [
                'success' => false,
                'message' => 'Sự kiện chưa bắt đầu hoặc đã kết thúc'
            ];
        }

        // Kiểm tra trạng thái của loại ranking cụ thể
        if (!$config->isTypeActive($type)) {
            return [
                'success' => false,
                'message' => 'Sự kiện này chưa bắt đầu hoặc đã kết thúc'
            ];
        }

    // Kiểm tra thời gian
    $now = now();
    $endTime = isset($config->type_end_time[$type]) 
        ? Carbon::parse($config->type_end_time[$type])
        : null;

    if (!$endTime) {
        return [
            'success' => false,
            'message' => 'Không tìm thấy thông tin thời gian sự kiện'
        ];
    }

    // Kiểm tra xem sự kiện đã kết thúc chưa
    if ($now->lt($endTime)) {
        return [
            'success' => false,
            'message' => 'Vui lòng đợi đến khi sự kiện kết thúc để nhận phần thưởng'
        ];
    }

    return ['success' => true];
}

public function claimRankingReward(Request $request)
{
    try {
        // Map type từ request sang database type
        $typeMapping = [
            'top-recharge' => 'recharge',
            'top-spend' => 'spend',
            'level' => 'level',
            'honor' => 'honor',
            'gong' => 'gong'
        ];

        $type = $request->type;
        $rank = $request->rank;
        $mappedType = $typeMapping[$type] ?? $type;

        \Log::info('Starting claim ranking reward', [
            'original_type' => $type,
            'mapped_type' => $mappedType,
            'rank' => $rank,
            'user_id' => Auth::id()
        ]);

        // Validate input cơ bản
        if (!$type || !$rank) {
            return response()->json([
                'success' => false,
                'message' => 'Thiếu thông tin cần thiết'
            ], 400);
        }

        // Validate yêu cầu với type đã được map
        $validation = $this->validateClaimRequest($mappedType);
        if (!$validation['success']) {
            return response()->json($validation, 400);
        }

        $user = Auth::user();
        DB::beginTransaction();
        
        try {
            // Kiểm tra đã nhận quà chưa
            $hasClaimedReward = DB::table('ranking_claims')
                ->where('user_id', $user->id)
                ->where('ranking_type', $mappedType)
                ->where('rank', $rank)
                ->exists();

            \Log::info('Checking claimed status', [
                'user_id' => $user->id,
                'type' => $mappedType,
                'rank' => $rank,
                'has_claimed' => $hasClaimedReward
            ]);

            if ($hasClaimedReward) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã nhận phần thưởng này rồi'
                ], 400);
            }

            // Kiểm tra xếp hạng hiện tại
            $currentRank = $this->getCurrentRank($user->id, $type);

            \Log::info('Checking current rank', [
                'user_id' => $user->id,
                'type' => $mappedType,
                'current_rank' => $currentRank,
                'requested_rank' => $rank
            ]);

            if ($currentRank !== (int)$rank) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không đủ điều kiện nhận phần thưởng này'
                ], 400);
            }

            // Lấy phần thưởng
            $rewards = RankingReward::where('ranking_type', $mappedType)
                ->where('rank_from', $rank)
                ->with('product')
                ->get();

            \Log::info('Found rewards', [
                'count' => $rewards->count(),
                'rewards' => $rewards->map(function($reward) {
                    return [
                        'product_id' => $reward->product_id,
                        'product_name' => $reward->product->prd_name
                    ];
                })
            ]);

            if ($rewards->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy phần thưởng'
                ], 404);
            }

            // Thêm phần thưởng vào inventory
            foreach ($rewards as $reward) {
                $maxOrderIdx = DB::connection('cuuamsql')
                    ->table('Tbl_Cash_Inven')
                    ->max('order_idx') ?? 0;

                DB::connection('cuuamsql')->table('Tbl_Cash_Inven')->insert([
                    'order_idx' => $maxOrderIdx + 1,
                    'item_code' => $reward->product->prd_code,
                    'item_user_id' => $user->userid,
                    'item_server_code' => 0,
                    'item_present' => 0,
                    'order_input_date' => Carbon::now()
                ]);
            }

            // Lưu lịch sử nhận quà
            DB::table('ranking_claims')->insert([
                'user_id' => $user->id,
                'ranking_type' => $mappedType,
                'rank' => $rank,
                'claimed_at' => Carbon::now()
            ]);

            // Lưu log giao dịch
            foreach ($rewards as $reward) {
                TransactionHistory::create([
                    'user_id' => $user->id,
                    'product_id' => $reward->product_id,
                    'type' => 4, // Type 4 cho quà ranking
                    'qty' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'transaction_hash' => hash('sha256', "{$user->id}_{$reward->product_id}_ranking_{$mappedType}_{$rank}_{time()}")
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Nhận quà thành công',
                'data' => [
                    'gifts' => $rewards->map(function($reward) {
                        return [
                            'name' => $reward->product->prd_name,
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
        \Log::error('Error in claimRankingReward', [
            'error' => $e->getMessage(),
            'original_type' => $type,
            'mapped_type' => $mappedType ?? null,
            'rank' => $request->rank,
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra khi nhận quà'
        ], 500);
    }
}

    private function getCurrentRank($userId, $type)
    {
    // Log thông tin đầu vào
    \Log::info('Getting current rank', [
        'user_id' => $userId,
        'type' => $type
    ]);

    // Lấy thông tin user
    $user = User::find($userId);
    if (!$user) {
        \Log::info('User not found', ['user_id' => $userId]);
        return null;
    }

    \Log::info('Found user', [
        'user_id' => $userId,
        'userid' => $user->userid
    ]);

    
        // Map type sang đúng loại ranking
        $mappedType = $this->typeMapping[$type] ?? $type;
    
        switch ($mappedType) {
            case 'recharge':
                return $this->getRechargeRank($userId);
                
            case 'spend':
                return $this->getSpendRank($userId);
                
                case 'level':
                    // Sửa lại query để sử dụng user_id thay vì userid
                    $characterQuery = NDV01CharacState::where('hiding', 0)
                        ->whereHas('NDV01Charac', function($query) use ($user) {
                            $query->where('user_id', $user->userid); // Sửa từ userid sang user_id
                        });
                    
                    // Log câu query để debug    
                    \Log::info('Character query', [
                        'sql' => $characterQuery->toSql(),
                        'bindings' => $characterQuery->getBindings()
                    ]);
                    
                    $character = $characterQuery->first();
                    
                    \Log::info('Found character for level ranking', [
                        'user_id' => $userId,
                        'userid' => $user->userid,
                        'character_found' => $character ? true : false,
                        'unique_id' => $character ? $character->unique_id : null,
                        'character_details' => $character ? [
                            'inner_level' => $character->inner_level,
                            'jin' => $character->jin,
                            'levelup_time' => $character->levelup_time,
                            'charac_name' => $character->NDV01Charac->chr_name ?? null
                        ] : null
                    ]);
                        
                    if (!$character) {
                        return null;
                    }
                    
                    $rank = $this->getLevelRank($character->unique_id);
                    
                    \Log::info('Calculated rank', [
                        'character_id' => $character->unique_id,
                        'rank' => $rank
                    ]);
                    
                    return $rank;
                
            case 'honor':
                // Lấy character state của user
                $character = NDV01CharacState::where('hiding', 0)
                    ->whereHas('NDV01Charac', function($query) use ($user) {
                        $query->where('chr_name', $user->userid);
                    })->first();
                    
                if (!$character) return null;
                return $this->getHonorRank($character->unique_id);
                
            case 'gong':
                // Lấy character state của user
                $character = NDV01CharacState::where('hiding', 0)
                    ->whereHas('NDV01Charac', function($query) use ($user) {
                        $query->where('chr_name', $user->userid);
                    })->first();
                    
                if (!$character) return null;
                return $this->getGongRank($character->unique_id);
                
            default:
                return null;
        }
    }

private function getRechargeRank($userId)
{
    // Log để debug
    \Log::info('Getting recharge rank', [
        'user_id' => $userId
    ]);

    $user = User::find($userId);
    if (!$user) {
        \Log::info('User not found', ['user_id' => $userId]);
        return null;
    }

    // Lấy thông tin sự kiện đang diễn ra
    $config = RankingEventConfig::first();
    $startTime = isset($config->type_start_time['recharge']) 
        ? Carbon::parse($config->type_start_time['recharge']) 
        : null;
    $endTime = isset($config->type_end_time['recharge']) 
        ? Carbon::parse($config->type_end_time['recharge']) 
        : null;

    if (!$startTime || !$endTime) {
        \Log::info('Event time not configured', ['user_id' => $userId]);
        return null;
    }

    // Lấy danh sách người dùng và tổng điểm nạp từ TransactionHistory
    $usersRanking = User::select('users.id')
        ->selectRaw('SUM(transaction_history.raw_coin) as total_recharge')
        ->leftJoin('transaction_history', function($join) use ($startTime, $endTime) {
            $join->on('users.id', '=', 'transaction_history.user_id')
                 ->where('transaction_history.type', '=', 1) // Type 1 là nạp tiền
                 ->where('transaction_history.created_at', '>=', $startTime)
                 ->where('transaction_history.created_at', '<=', $endTime);
        })
        ->groupBy('users.id')
        ->havingRaw('SUM(transaction_history.raw_coin) > 0')
        ->orderByRaw('SUM(transaction_history.raw_coin) DESC')
        ->get();

    // Tìm thứ hạng của user hiện tại
    $rank = $usersRanking->search(function($user) use ($userId) {
        return $user->id == $userId;
    });

    \Log::info('Calculated recharge rank', [
        'rank' => $rank !== false ? $rank + 1 : null,
        'total_users' => $usersRanking->count()
    ]);

    return $rank !== false ? $rank + 1 : null;
}

    private function getSpendRank($userId)
    {
        // Log để debug
        \Log::info('Getting spend rank', [
            'user_id' => $userId
        ]);
    
        $rank = User::orderBy('spent_coin', 'desc')
            ->where('spent_coin', '>', 0) // Chỉ lấy những người có điểm tiêu
            ->get()
            ->search(function($user) use ($userId) {
                return $user->id == $userId;
            });
    
        \Log::info('Calculated spend rank', [
            'rank' => $rank !== false ? $rank + 1 : null
        ]);
    
        return $rank !== false ? $rank + 1 : null;
    }

    private function getLevelRank($uniqueId)
    {
        // Log thông tin uniqueId đầu vào
        \Log::info('Getting level rank for character', [
            'unique_id' => $uniqueId
        ]);
    
        // Lấy character state của người chơi
        $character = NDV01CharacState::where('unique_id', $uniqueId)
            ->where('hiding', 0)
            ->first();
    
        // Log thông tin character tìm được
        \Log::info('Found character state', [
            'found' => $character ? true : false,
            'level' => $character ? $character->inner_level : null,
            'jin' => $character ? $character->jin : null,
            'levelup_time' => $character ? $character->levelup_time : null,
            'hiding' => $character ? $character->hiding : null
        ]);
    
        if (!$character) {
            return null;
        }
    
        // Đếm số nhân vật có level cao hơn hoặc bằng và thời gian lên cấp sớm hơn
        $higherRank = NDV01CharacState::where('hiding', 0)
            ->where(function($query) use ($character) {
                $query->where('inner_level', '>', $character->inner_level)
                    ->orWhere(function($q) use ($character) {
                        $q->where('inner_level', '=', $character->inner_level)
                            ->where('jin', '>', $character->jin)
                        ->orWhere(function($q2) use ($character) {
                            $q2->where('inner_level', '=', $character->inner_level)
                                ->where('jin', '=', $character->jin)
                                ->where('levelup_time', '<', $character->levelup_time);
                        });
                    });
            })
            ->count();
    
        $rank = $higherRank + 1;
    
        // Log kết quả tính rank
        \Log::info('Calculated level rank', [
            'higher_rank_count' => $higherRank,
            'final_rank' => $rank
        ]);
    
        return $rank;
    }
    
    private function getHonorRank($uniqueId)  
    {
        $query = NDV01CharacState::where('hiding', 0)
            ->select('unique_id', 'honor')
            ->orderBy('honor', 'desc')
            ->get();
    
        $rank = $query->search(function($char) use ($uniqueId) {
            return $char->unique_id === $uniqueId;
        });
    
        return $rank !== false ? $rank + 1 : null;
    }
    
    private function getGongRank($uniqueId)
    {
        $query = NDV01CharacState::where('hiding', 0)
            ->select('unique_id', 'gong')
            ->orderBy('gong', 'desc')
            ->get();
    
        $rank = $query->search(function($char) use ($uniqueId) {
            return $char->unique_id === $uniqueId;
        });
    
        return $rank !== false ? $rank + 1 : null;
    }

    public function getRankingRewards($type)
    {
        try {
            $mappedType = $this->typeMapping[$type] ?? $type;
            $user = Auth::user();
            $config = RankingEventConfig::first();
            
            if (!$config) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chưa có cấu hình sự kiện'
                ], 400);
            }
        
            // Kiểm tra trạng thái của type cụ thể
            $typeStatus = $config->type_status[$mappedType] ?? null;
            if ($typeStatus !== "1") {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện này chưa được bật'
                ], 400);
            }
        
            // Lấy thời gian sự kiện
            $startTime = isset($config->type_start_time[$mappedType]) 
                ? Carbon::parse($config->type_start_time[$mappedType]) 
                : null;
            $endTime = isset($config->type_end_time[$mappedType]) 
                ? Carbon::parse($config->type_end_time[$mappedType]) 
                : null;
            
            $now = Carbon::now();
            $isEventEnded = $endTime ? $now->gt($endTime) : false;
    
            // Lấy xếp hạng hiện tại của người dùng
            $currentRank = $user ? $this->getCurrentRank($user->id, $type) : null;
    
            // Lấy phần thưởng đã nhận
            $claimedRewards = DB::table('ranking_claims')
                ->where('user_id', $user ? $user->id : 0)
                ->where('ranking_type', $mappedType)
                ->pluck('rank')
                ->toArray();
        
            // Lấy phần thưởng
            $rewards = RankingReward::where('ranking_type', $mappedType)
                ->with('product')
                ->orderBy('rank_from')
                ->get();
        
            // Map response với trạng thái nút cho từng phần thưởng
$mappedRewards = $rewards->map(function($reward) use ($currentRank, $claimedRewards, $isEventEnded) {
    $buttonStatus = $this->getButtonStatus(
        $reward->rank_from,
        $currentRank,
        in_array($reward->rank_from, $claimedRewards),
        $isEventEnded
    );

    // Kiểm tra sản phẩm tồn tại không
    if (!$reward->product) {
        return [
            'rank_from' => $reward->rank_from,
            'rank_to' => $reward->rank_to,
            'button_status' => $buttonStatus,
            'products' => [] // Trả về mảng rỗng nếu không có sản phẩm
        ];
    }

    return [
        'rank_from' => $reward->rank_from,
        'rank_to' => $reward->rank_to,
        'button_status' => $buttonStatus,
        'products' => [
            [
                'id' => $reward->product->id,
                'name' => $reward->product->prd_name,
                'description' => $reward->product->prd_description,
                'image' => Storage::url('products/' . $reward->product->prd_thunbar)
            ]
        ]
    ];
});
        
            return response()->json([
                'success' => true,
                'data' => [
                    'rewards' => $mappedRewards,
                    'event_time' => [
                        'start' => $startTime ? $startTime->toDateTimeString() : null,
                        'end' => $endTime ? $endTime->toDateTimeString() : null,
                        'is_ended' => $isEventEnded
                    ],
                    'current_rank' => $currentRank
                ]
            ]);
        
        } catch (\Exception $e) {
            \Log::error('Error in getRankingRewards: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy thông tin phần thưởng'
            ], 500);
        }
    }
    
    private function getButtonStatus($rewardRank, $currentRank, $isClaimed, $isEventEnded)
    {
        if ($isClaimed) {
            return [
                'status' => 'claimed',
                'message' => 'Đã nhận phần thưởng'
            ];
        }
    
        if (!$isEventEnded) {
            return [
                'status' => 'waiting',
                'message' => 'Chờ kết thúc sự kiện'
            ];
        }
    
        if ($currentRank !== $rewardRank) {
            return [
                'status' => 'not_eligible',
                'message' => 'Chưa đủ điều kiện'
            ];
        }
    
        return [
            'status' => 'can_claim',
            'message' => 'Nhận quà'
        ];
    }
    

    
}