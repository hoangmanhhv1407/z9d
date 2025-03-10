<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QRCodeController extends Controller
{
    /**
     * Tạo QR code sử dụng Quick Link của VietQR
     */
    public function generateQRCode(Request $request)
    {
        try {
            $userId = auth()->id();
            
            // Lấy số tiền từ request
            $amount = $request->input('amount');
            
            // Thông tin ngân hàng từ dự án
            $bankId = '970422'; // Mã BIN của MB Bank (hoặc có thể dùng 'MBBank')
            $accountNumber = '90511239999';
            $accountName = urlencode('LE VIET ANH');
            $description = urlencode("CLTB9D{$userId}");
            $template = 'compact2';
            
            // Tạo Quick Link theo cú pháp của VietQR
            $quickLink = "https://img.vietqr.io/image/{$bankId}-{$accountNumber}-{$template}.png";
            
            // Thêm các tham số bổ sung
            $quickLink .= "?amount={$amount}";
            $quickLink .= "&addInfo={$description}";
            $quickLink .= "&accountName={$accountName}";
            
            return response()->json([
                'success' => true,
                'qrCode' => $quickLink,
                'bankInfo' => [
                    'accountNumber' => $accountNumber,
                    'description' => "CLTB9D{$userId}"
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error generating QR code: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo mã QR. Vui lòng thử lại sau.'
            ], 500);
        }
    }
}