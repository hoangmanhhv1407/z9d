<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Pusher\Pusher;
use Illuminate\Support\Facades\Log;

class PusherNotificationController extends Controller
{
    protected $pusher;

    public function __construct()
    {
        $appKey = config('broadcasting.connections.pusher.key');
        $appSecret = config('broadcasting.connections.pusher.secret');
        $appId = config('broadcasting.connections.pusher.app_id');
        $appCluster = config('broadcasting.connections.pusher.options.cluster');

        Log::info('Initializing Pusher', [
            'appKey' => $appKey,
            'appId' => $appId,
            'appCluster' => $appCluster
        ]);

        if (!$appKey || !$appSecret || !$appId || !$appCluster) {
            Log::error('Pusher configuration is missing or incomplete');
            return;
        }

        try {
            $this->pusher = new Pusher(
                $appKey,
                $appSecret,
                $appId,
                [
                    'cluster' => $appCluster,
                    'useTLS' => true
                ]
            );
            Log::info('Pusher initialized successfully');
        } catch (\Exception $e) {
            Log::error('Failed to initialize Pusher', ['error' => $e->getMessage()]);
        }
    }

    public function sendNotification($userId, $message, $amount, $currentCoins)
    {
        if (!$this->pusher) {
            Log::error('Pusher is not initialized');
            return;
        }

        try {
            Log::info('Attempting to send Pusher notification', [
                'userId' => $userId,
                'message' => $message,
                'amount' => $amount
            ]);

            $this->pusher->trigger('user-channel-' . $userId, 'deposit-event', [
                'message' => $message,
                'amount' => $amount,
                'currentCoins' => $currentCoins // Thêm số xu hiện tại vào dữ liệu gửi đi
            ]);
            
            Log::info("Pusher notification sent successfully", ['userId' => $userId]);
        } catch (\Exception $e) {
            Log::error("Error sending Pusher notification", [
                'error' => $e->getMessage(),
                'userId' => $userId
            ]);
        }
    }
}