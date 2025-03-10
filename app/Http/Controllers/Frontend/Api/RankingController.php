<?php

namespace App\Http\Controllers\Frontend\Api;

use App\Http\Controllers\Controller;
use App\Models\NDV01CharacState;
use App\Models\NDV02Elixir;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeSetting;

class RankingController extends Controller
{
    /**
     * Constants for configuration
     */
    private const DEFAULT_PER_PAGE = 100;
    private const PARTY_NAMES = [
        0 => 'Lãng Nhân',
        1 => 'Cái Bang',
        2 => 'Bí Cung',
        3 => 'Thiếu Lâm',
        4 => 'Lục Lâm',
        5 => 'Võ Đang',
        6 => 'Ma Giáo',
        7 => 'Thế Gia',
        8 => 'Hắc Long'
    ];
    
    private const CLASS_NAMES = [
        0 => 'Lãng Nhân',
        1 => 'Chiến Đấu',
        2 => 'Chữa Trị',
        3 => 'Hoàn Hảo',
        4 => 'Khí Công',
        5 => 'Sát Thủ',
        6 => 'Hộ Tâm',
        7 => 'Chú Thuật',
        8 => 'Ngoại Công'
    ];

    /**
     * Lấy tất cả bảng xếp hạng
     */
    public function getRankings(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', self::DEFAULT_PER_PAGE);

            $rankings = [
                'level' => $this->getLevelData($page, $perPage),
                'gold' => $this->getGoldData($page, $perPage),
                'honor' => $this->getHonorData($page, $perPage),
                'gong' => $this->getGongData($page, $perPage)
            ];

            return response()->json([
                'success' => true,
                'data' => $rankings
            ]);

        } catch (\Exception $e) {
            Log::error('Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải bảng xếp hạng', $e);
        }
    }

    /**
     * Lấy xếp hạng level
     */
    public function getLevelRankings(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', self::DEFAULT_PER_PAGE);
            
            $rankings = $this->getLevelData($page, $perPage);
            return response()->json([
                'success' => true,
                'data' => $rankings['data'],
                'meta' => $rankings['meta']
            ]);
        } catch (\Exception $e) {
            Log::error('Level Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải xếp hạng level', $e);
        }
    }

    /**
     * Lấy xếp hạng vàng
     */
    public function getGoldRankings(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', self::DEFAULT_PER_PAGE);

            $rankings = $this->getGoldData($page, $perPage);
            return response()->json([
                'success' => true,
                'data' => $rankings['data'],
                'meta' => $rankings['meta']
            ]);
        } catch (\Exception $e) {
            Log::error('Gold Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải xếp hạng vàng', $e);
        }
    }

    /**
     * Lấy xếp hạng danh vọng
     */
    public function getHonorRankings(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', self::DEFAULT_PER_PAGE);

            $rankings = $this->getHonorData($page, $perPage);
            return response()->json([
                'success' => true,
                'data' => $rankings['data'],
                'meta' => $rankings['meta']
            ]);
        } catch (\Exception $e) {
            Log::error('Honor Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải xếp hạng danh vọng', $e);
        }
    }

    /**
     * Lấy xếp hạng ác danh
     */
    public function getGongRankings(Request $request): JsonResponse
    {
        try {
            $page = $request->get('page', 1);
            $perPage = $request->get('per_page', self::DEFAULT_PER_PAGE);

            $rankings = $this->getGongData($page, $perPage);
            return response()->json([
                'success' => true,
                'data' => $rankings['data'],
                'meta' => $rankings['meta']
            ]);
        } catch (\Exception $e) {
            Log::error('Gong Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải xếp hạng ác danh', $e);
        }
    }

    /**
     * Tạo metadata cho response
     */
    private function createMetadata(int $total, int $page, int $perPage): array
    {
        return [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    /**
     * Xử lý và trả về error response
     */
    private function errorResponse(string $message, \Exception $e): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }

    private function getLevelData(int $page, int $perPage): array
    {
        $query = NDV01CharacState::where('hiding', 0)
            ->select('unique_id', 'inner_level', 'levelup_time', 'level_rate')
            ->orderBy('inner_level', 'desc') // Sắp xếp theo level giảm dần
            ->orderBy('jin', 'desc') // Nếu level bằng nhau, sắp xếp theo kinh nghiệm giảm dần
            ->orderBy('levelup_time', 'asc') // Nếu tiếp tục bằng nhau, sắp xếp theo thời gian tăng dần    
            ->with(['NDV01Charac' => function ($query) {
                $query->select('unique_id', 'chr_name', 'party', 'class');
            }]);

        $total = $query->count();
        $data = $query->skip(($page - 1) * $perPage)
                     ->take($perPage)
                     ->get();

        return [
            'data' => $this->transformLevelData($data),
            'meta' => $this->createMetadata($total, $page, $perPage)
        ];
    }

    private function getGoldData(int $page, int $perPage): array
    {
        $query = NDV02Elixir::select('cuid', 'money')
            ->orderBy('money', 'desc')
            ->with(['NDV01Charac' => function ($query) {
                $query->select('unique_id', 'chr_name', 'party', 'class')
                    ->whereHas('NDV01CharacState', function ($query) {
                        $query->where('hiding', 0);
                    });
            }]);

        $total = $query->count();
        $data = $query->skip(($page - 1) * $perPage)
                     ->take($perPage)
                     ->get()
                     ->filter(function ($item) {
                         return $item->NDV01Charac !== null;
                     });

        return [
            'data' => $this->transformGoldData($data),
            'meta' => $this->createMetadata($total, $page, $perPage)
        ];
    }

    private function getHonorData(int $page, int $perPage): array
    {
        $query = NDV01CharacState::where('hiding', 0)
            ->select('unique_id', 'honor')
            ->orderBy('honor', 'desc')
            ->with(['NDV01Charac' => function ($query) {
                $query->select('unique_id', 'chr_name', 'party', 'class');
            }]);

        $total = $query->count();
        $data = $query->skip(($page - 1) * $perPage)
                     ->take($perPage)
                     ->get();

        return [
            'data' => $this->transformHonorData($data),
            'meta' => $this->createMetadata($total, $page, $perPage)
        ];
    }

    private function getGongData(int $page, int $perPage): array
    {
        $query = NDV01CharacState::where('hiding', 0)
            ->select('unique_id', 'gong')
            ->orderBy('gong', 'desc')
            ->with(['NDV01Charac' => function ($query) {
                $query->select('unique_id', 'chr_name', 'party', 'class');
            }]);

        $total = $query->count();
        $data = $query->skip(($page - 1) * $perPage)
                     ->take($perPage)
                     ->get();

        return [
            'data' => $this->transformGongData($data),
            'meta' => $this->createMetadata($total, $page, $perPage)
        ];
    }

    private function transformLevelData($data): array
    {
        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'chr_name' => $item->NDV01Charac->chr_name ?? 'Unknown',
                'inner_level' => $item->inner_level,
                'levelup_time' => $item->levelup_time,
                'level_rate' => $item->level_rate,
                'party_name' => self::PARTY_NAMES[$item->NDV01Charac->party] ?? 'Không xác định',
                'class_name' => self::CLASS_NAMES[$item->NDV01Charac->class] ?? 'Không xác định'
            ];
        })->toArray();
    }

    private function transformGoldData($data): array
    {
        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'chr_name' => $item->NDV01Charac->chr_name ?? 'Unknown',
                'money' => $item->money,
                'party_name' => self::PARTY_NAMES[$item->NDV01Charac->party] ?? 'Không xác định',
                'class_name' => self::CLASS_NAMES[$item->NDV01Charac->class] ?? 'Không xác định'
            ];
        })->toArray();
    }

    private function transformHonorData($data): array
    {
        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'chr_name' => $item->NDV01Charac->chr_name ?? 'Unknown',
                'honor' => $item->honor,
                'party_name' => self::PARTY_NAMES[$item->NDV01Charac->party] ?? 'Không xác định',
                'class_name' => self::CLASS_NAMES[$item->NDV01Charac->class] ?? 'Không xác định'
            ];
        })->toArray();
    }

    private function transformGongData($data): array
    {
        return $data->map(function ($item, $index) {
            return [
                'rank' => $index + 1,
                'chr_name' => $item->NDV01Charac->chr_name ?? 'Unknown',
                'gong' => $item->gong,
                'party_name' => self::PARTY_NAMES[$item->NDV01Charac->party] ?? 'Không xác định',
                'class_name' => self::CLASS_NAMES[$item->NDV01Charac->class] ?? 'Không xác định'
            ];
        })->toArray();
    }
    public function getTopRechargeRankings(Request $request): JsonResponse
    {
        try {
            $timeSetting = TimeSetting::find(3);
            $eventActive = $timeSetting && $timeSetting->status == 1 && Carbon::now()->between(
                Carbon::parse($timeSetting->day_start),
                Carbon::parse($timeSetting->day_end)
            );
    
            $data = $eventActive ? $this->getTopRechargeData($request->get('page', 1), $request->get('per_page', self::DEFAULT_PER_PAGE)) : ['data' => [], 'meta' => $this->createMetadata(0, 1, 10)];
    
            return response()->json([
                'success' => true,
                'data' => $data['data'],
                'meta' => $data['meta'],
                'event_active' => $eventActive,
            ]);
        } catch (\Exception $e) {
            Log::error('Top Recharge Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải xếp hạng nạp', $e);
        }
    }
    
private function getTopRechargeData(int $page, int $perPage): array
{
    $timeSetting = TimeSetting::find(3);

    // Kiểm tra xem sự kiện có đang diễn ra không
    $now = Carbon::now();
    if ($timeSetting && $timeSetting->status == 1) {
        $start = Carbon::parse($timeSetting->day_start);
        $end = Carbon::parse($timeSetting->day_end);

        if ($now->greaterThan($end)) {
            // Reset dữ liệu nếu sự kiện đã kết thúc
            $timeSetting->status = 0;
            $timeSetting->save();
        } elseif ($now->greaterThan($start)) {
            // Lấy danh sách top dựa trên tổng raw_coin từ TransactionHistory
            // Chỉ lấy các giao dịch trong khoảng thời gian của sự kiện
            $query = User::select('users.id', 'users.userid')
                ->selectRaw('SUM(transaction_history.raw_coin) as total_recharge')
                ->leftJoin('transaction_history', function($join) use ($start, $end) {
                    $join->on('users.id', '=', 'transaction_history.user_id')
                         ->where('transaction_history.type', '=', 1) // Type 1 là nạp tiền
                         ->where('transaction_history.created_at', '>=', $start)
                         ->where('transaction_history.created_at', '<=', $end);
                })
                ->groupBy('users.id', 'users.userid')
                ->havingRaw('SUM(transaction_history.raw_coin) > 0')
                ->orderByRaw('SUM(transaction_history.raw_coin) DESC');

            $total = $query->count();
            $data = $query->skip(($page - 1) * $perPage)
                         ->take($perPage)
                         ->get();

            return [
                'data' => $this->transformTopRechargeData($data, $page, $perPage),
                'meta' => $this->createMetadata($total, $page, $perPage)
            ];
        }
    }

    // Trả về danh sách trống nếu không có sự kiện
    return [
        'data' => [],
        'meta' => $this->createMetadata(0, $page, $perPage)
    ];
}

private function transformTopRechargeData($data, $page, $perPage): array 
{
    return $data->map(function ($item, $index) use ($page, $perPage) {
        return [
            'rank' => $index + 1 + (($page - 1) * $perPage),
            'userid' => $this->maskUserid($item->userid), // Che giấu userid 
            'char_event' => $item->total_recharge ?? 0,
        ];
    })->toArray();
}

    public function getTopSpendRankings(Request $request): JsonResponse
    {
        try {
            $timeSetting = TimeSetting::find(3);
            $eventActive = $timeSetting && $timeSetting->status == 1 && Carbon::now()->between(
                Carbon::parse($timeSetting->day_start),
                Carbon::parse($timeSetting->day_end)
            );
    
            $data = $eventActive ? $this->getTopSpendData($request->get('page', 1), $request->get('per_page', self::DEFAULT_PER_PAGE)) : ['data' => [], 'meta' => $this->createMetadata(0, 1, 10)];
    
            return response()->json([
                'success' => true,
                'data' => $data['data'],
                'meta' => $data['meta'],
                'event_active' => $eventActive,
                'event_time' => $eventActive ? [
                    'start' => $timeSetting->day_start,
                    'end' => $timeSetting->day_end
                ] : null
            ]);
        } catch (\Exception $e) {
            Log::error('Top Spend Ranking Error: ' . $e->getMessage());
            return $this->errorResponse('Có lỗi xảy ra khi tải xếp hạng tiêu', $e);
        }
    }
    
    private function getTopSpendData(int $page, int $perPage): array
    {
        $timeSetting = TimeSetting::find(3);
    
        // Kiểm tra xem sự kiện có đang diễn ra không
        $now = Carbon::now();
        if ($timeSetting && $timeSetting->status == 1) {
            $start = Carbon::parse($timeSetting->day_start);
            $end = Carbon::parse($timeSetting->day_end);
    
            if ($now->greaterThan($end)) {
                // Reset dữ liệu nếu sự kiện đã kết thúc
                User::where('spent_coin', '!=', '0')->update(['spent_coin' => '0']);
                $timeSetting->status = 0;
                $timeSetting->save();
            } elseif ($now->greaterThan($start)) {
                // Lấy danh sách top nếu sự kiện đang diễn ra
                $query = User::orderBy('spent_coin', 'desc');
    
                $total = $query->count();
                $data = $query->skip(($page - 1) * $perPage)
                             ->take($perPage)
                             ->get();
    
                return [
                    'data' => $this->transformTopSpendData($data, $page, $perPage),
                    'meta' => $this->createMetadata($total, $page, $perPage)
                ];
            }
        }
    
        // Trả về danh sách trống nếu không có sự kiện
        return [
            'data' => [],
            'meta' => $this->createMetadata(0, $page, $perPage)
        ];
    }
    
private function transformTopSpendData($data, $page, $perPage): array
{
    return $data->map(function ($item, $index) use ($page, $perPage) {
        return [
            'rank' => $index + 1 + (($page - 1) * $perPage),
            'userid' => $this->maskUserid($item->userid), // Che giấu userid
            'spent_coin' => $item->spent_coin ?? 0,
        ];
    })->toArray();
}
    
    // Hàm cập nhật số xu đã tiêu
    public function spendCoin($amount) {
        $user = Auth::user();
        if ($user->coin >= $amount) {
            DB::beginTransaction();
            try {
                // Trừ xu
                $user->coin -= $amount;
                
                // Kiểm tra và cập nhật spent_coin nếu trong thời gian sự kiện
                $timeSetting = TimeSetting::where('id', 3)->first();
                $now = time();
                $start = strtotime($timeSetting->day_start);
                $end = strtotime($timeSetting->day_end);
    
                if ($timeSetting->status == 1 && $now > $start && $now < $end) {
                    $user->spent_coin += $amount;
                }
    
                $user->save();
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Spend Coin Error: ' . $e->getMessage());
                return false;
            }
        }
        return false;
    }

// Thêm phương thức mới
private function maskUserid($userid)
{
    if (!$userid) return 'Unknown';
    
    if (strlen($userid) <= 5) {
        return substr($userid, 0, 1) . str_repeat('*', 4);
    } else {
        $visibleChars = min(2, floor(strlen($userid) / 3));
        return substr($userid, 0, $visibleChars) . 
               str_repeat('*', strlen($userid) - $visibleChars - 1) .
               substr($userid, -1);
    }
}

}
