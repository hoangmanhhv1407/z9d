<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CassoTransactionHistory;
use App\Models\User;
use Carbon\Carbon;

class CassoTransactionController extends Controller
{
    public function checkCassoTransaction(Request $request)
    {
        $amount = $request->input('amount');
        $userId = $request->input('user_id');
        $user = User::findOrFail($userId);

        // Kiểm tra giao dịch trong khoảng thời gian gần đây (ví dụ: 10 phút trước)
        $recentTransaction = CassoTransactionHistory::where('user_id', $userId)
            ->where('amount', $amount)
            ->where('description', 'LIKE', "%NAP9D$userId%")
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->first();

        if ($recentTransaction) {
            // Cập nhật số xu của người dùng
            $user->balance += $amount;
            $user->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }
}