<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;
use App\Http\Controllers\Controller;
use App\Models\NDV01CharacState;
use App\Models\NDV01Charac;
use App\Models\GameConnect;
use App\Models\MemLog;
use App\Models\TimeSetting;
use App\Models\TransactionHistory;
use App\Models\CassoTransactionHistory;
use App\Http\Controllers\Admin\TransactionHistoryController;
use Illuminate\Support\Facades\Log;
use App\Events\NewTransactionEvent;

class CassoWebhookController extends Controller
{
    protected $secureToken = 'hjkyhjtyjghnghn67567';
    protected $orderPrefix = 'CLTB9D';

    protected $pusherNotificationController;

    public function __construct(PusherNotificationController $pusherNotificationController)
    {
        $this->pusherNotificationController = $pusherNotificationController;
    }
    
    public function handle(Request $request)
    {
        Log::info('Received webhook from Casso', ['request' => $request->all()]);

        // Kiểm tra secure token
        if ($request->header('Secure-Token') !== $this->secureToken) {
            Log::warning('Invalid secure token received', ['token' => $request->header('Secure-Token')]);
            return response()->json(['error' => 'Invalid secure token'], 401);
        }

        $data = $request->json()->all();

        if ($data['error'] !== 0) {
            Log::error('Casso error', ['data' => $data]);
            return response()->json(['error' => 'Casso error'], 400);
        }

        foreach ($data['data'] as $transaction) {
            $this->processTransaction($transaction);
        }

        return response()->json(['message' => 'Webhook processed successfully']);
    }

    protected function processTransaction($transaction)
    {
        Log::info('Processing transaction', ['transaction' => $transaction]);

        $userId = $this->parseUserId($transaction['description']);
        if (!$userId) {
            Log::info("Không nhận dạng được user_id từ nội dung", ['description' => $transaction['description']]);
            return;
        }

        $user = User::find($userId);
        if (!$user) {
            Log::error("User không tồn tại", ['userId' => $userId]);
            return;
        }

        $amount = $transaction['amount'];
        $transactionId = $transaction['id'];

        // Kiểm tra xem giao dịch đã được xử lý chưa
        $existingTransaction = TransactionHistory::where('code', $transactionId)->first();
        if ($existingTransaction) {
            Log::info("Giao dịch đã được xử lý trước đó", ['transactionId' => $transactionId]);
            return;
        }

        // Bắt đầu giao dịch database
        \DB::beginTransaction();

        try {
            // Quy đổi số tiền thành xu theo tỷ lệ 1000:1 (100.000 VND = 100 xu)
            $rawAmount = (int) ($amount / 1000);
            $totalCoin = $rawAmount;
            $totalCoin2 = $rawAmount;

            $timeSetting = TimeSetting::where('id', 1)->where('status', 1)->first();
            $timeSetting2 = TimeSetting::where('id', 2)->where('status', 1)->first();
            $timeSetting3 = TimeSetting::where('id', 3)->where('status', 1)->first();

            // Áp dụng bội số từ TimeSetting nếu có
            if (isset($timeSetting)) {
                $totalCoin = (int) ($rawAmount * $timeSetting->number);
            }

            if (isset($timeSetting2)) {
                $totalCoin2 = (int) ($rawAmount * $timeSetting2->number);
                $user->increment('accumulate', (int) $totalCoin2);
            }

            if (isset($timeSetting3)) {
                $now = Carbon::now()->timestamp;
                $start = Carbon::parse($timeSetting3->day_start)->timestamp;
                $end = Carbon::parse($timeSetting3->day_end)->timestamp;
                if ($now > $end) {
                    $timeSetting3->status = 0;
                    $timeSetting3->save();
                }
                if ($now > $start && $now < $end) {
                    // Sử dụng rawAmount thay vì amount để tính char_event
                    $user->increment('char_event', (int) $rawAmount);
                }
            }

            // Tạo bản ghi lịch sử giao dịch mới
            $newTransaction = new TransactionHistory([
                'user_id' => $userId,
                'code' => $transactionId,
                'coin' => $totalCoin,
				'raw_coin' => $rawAmount, // Thêm trường này để lưu số xu thực
                'type' => 1, // Giả sử 1 là cho nạp tiền
                'created_at' => Carbon::parse($transaction['when']),
                'updated_at' => Carbon::now(),
                'accumulate' => $totalCoin2
            ]);
            $newTransaction->save();

            // Cập nhật số xu của người dùng sử dụng $totalCoin
            $user->increment('coin', (int) $totalCoin);
            $currentCoins = $user->fresh()->coin;

            // Gửi thông báo qua Pusher với số xu hiện có
            $this->pusherNotificationController->sendNotification(
                $userId,
                "Nạp xu thành công!",
                $amount,
                $currentCoins
            );
            Log::info("Broadcasting NewTransactionEvent for user", ['userId' => $userId, 'amount' => $amount, 'currentCoins' => $currentCoins]);          
            
            // Phát sóng sự kiện cho client qua Laravel Echo
            event(new NewTransactionEvent($userId, $amount, $currentCoins));

            // Commit giao dịch
            \DB::commit();
            
            Log::info("Giao dịch xử lý thành công", [
                'userId' => $userId, 
                'amount' => $amount, 
                'rawAmount' => $rawAmount,
                'totalCoin' => $totalCoin
            ]);
            
            Log::info("Đã gửi thông báo Pusher", ['userId' => $userId, 'amount' => $amount, 'currentCoins' => $currentCoins]);
        } catch (\Exception $e) {
            // Nếu có lỗi, rollback giao dịch
            \DB::rollBack();
            Log::error("Lỗi khi xử lý giao dịch", ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    protected function parseUserId($description)
    {
        if (preg_match('/' . $this->orderPrefix . '(\d+)/i', $description, $matches)) {
            return intval($matches[1]);
        }
        Log::info("Không thể parse user ID", ['description' => $description]);
        return null;
    }
}