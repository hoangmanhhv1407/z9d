<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestPusherController extends Controller
{
    protected $pusherNotificationController;

    public function __construct(PusherNotificationController $pusherNotificationController)
    {
        $this->pusherNotificationController = $pusherNotificationController;
    }

    public function index()
    {
        return view('frontend.test-pusher');
    }

    public function triggerEvent(Request $request)
    {
        $userId = auth()->id(); // Đảm bảo người dùng đã đăng nhập
        $message = "Giao dịch thành công!";
        $amount = 1000; // Số xu test

        $this->pusherNotificationController->sendNotification($userId, $message, $amount);

        return response()->json(['success' => true, 'message' => 'Pusher event triggered']);
    }
}