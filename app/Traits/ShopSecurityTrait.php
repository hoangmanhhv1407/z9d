<?php

namespace App\Traits;

use App\Models\Product;
use App\Models\TransactionHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

trait ShopSecurityTrait 
{
    private function validateProductSecurity($product, $user, $quantity = 1)
    {
        $violations = [];

        // 1. Kiểm tra tính hợp lệ của sản phẩm từ cache
        $cachedProduct = Cache::get("product_{$product->id}");
        if ($cachedProduct && $cachedProduct->coin !== $product->coin) {
            $violations[] = [
                'type' => 'price_mismatch',
                'details' => 'Giá sản phẩm không khớp với dữ liệu gốc'
            ];
        }

        // 2. Kiểm tra giới hạn mua trong tuần
        if ($product->turn > 0) {
            $weeklyLimit = $this->checkWeeklyPurchaseLimit($product, $user);
            if ($weeklyLimit['exceeded']) {
                $violations[] = [
                    'type' => 'weekly_limit_exceeded',
                    'details' => 'Đã vượt quá giới hạn mua trong tuần',
                    'data' => $weeklyLimit
                ];
            }
        }

        // 3. Kiểm tra số lượng mua hợp lệ
        if ($quantity > 255 || $quantity < 1) {
            $violations[] = [
                'type' => 'invalid_quantity',
                'details' => 'Số lượng mua không hợp lệ'
            ];
        }

        // Ghi log nếu phát hiện vi phạm
        if (!empty($violations)) {
            $this->logSecurityViolations($user, $product, $violations);
        }

        return $violations;
    }

    private function checkWeeklyPurchaseLimit($product, $user)
    {
        $date = Carbon::now();
        $weekStart = $date->copy()->startOfWeek();
        $weekEnd = $date->copy()->endOfWeek();

        $purchasedCount = TransactionHistory::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->sum('qty');

        return [
            'exceeded' => $purchasedCount >= $product->turn,
            'current' => $purchasedCount,
            'limit' => $product->turn
        ];
    }

    private function logSecurityViolations($user, $product, $violations)
    {
        Log::warning('Shop security violations detected', [
            'user_id' => $user->id,
            'username' => $user->userid,
            'product_id' => $product->id,
            'violations' => $violations,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'timestamp' => Carbon::now()->toDateTimeString()
        ]);
    }
}