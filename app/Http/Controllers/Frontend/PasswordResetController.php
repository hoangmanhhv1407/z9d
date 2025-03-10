<?php

namespace App\Http\Controllers\Frontend;

use App\Models\PasswordReset;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;

class PasswordResetController extends Controller
{
    public function create(Request $request)
    {
        Log::info('Password reset request initiated', [
            'email' => $request->email,
            'username' => $request->username
        ]);

        try {
            // Validate đầu vào
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'username' => 'required|string'
            ], [
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'username.required' => 'Tên tài khoản không được để trống'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            // Tìm user
            $user = User::where([
                ['email', '=', $request->email],
                ['userid', '=', $request->username]
            ])->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy tài khoản với thông tin này'
                ], 404);
            }

            // Tạo mã xác nhận 6 số
            $verificationCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Lưu mã xác nhận vào bảng password_resets
            try {
                PasswordReset::updateOrCreate(
                    ['email' => $user->email],
                    [
                        'token' => $verificationCode,
                        'created_at' => Carbon::now()
                    ]
                );
            } catch (Exception $e) {
                Log::error('Error creating verification code', [
                    'error' => $e->getMessage()
                ]);
                throw new Exception('Lỗi khi tạo mã xác nhận');
            }

            // Gửi email
            try {
                Mail::send('emails.password-reset', [
                    'code' => $verificationCode,
                    'user' => $user
                ], function($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Mã xác nhận đặt lại mật khẩu - ' . config('app.name'));
                });

                return response()->json([
                    'success' => true,
                    'message' => 'Mã xác nhận đã được gửi đến email của bạn'
                ]);

            } catch (Exception $e) {
                Log::error('Mail sending error', [
                    'error' => $e->getMessage()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi gửi email'
                ], 500);
            }

        } catch (Exception $e) {
            Log::error('Password reset process failed', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra. Vui lòng thử lại sau'
            ], 500);
        }
    }

    public function verifyCode(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required|string|size:6'
            ], [
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'code.required' => 'Mã xác nhận không được để trống',
                'code.size' => 'Mã xác nhận phải có 6 ký tự'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $passwordReset = PasswordReset::where([
                ['email', $request->email],
                ['token', $request->code]
            ])->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã xác nhận không hợp lệ'
                ], 400);
            }

            // Kiểm tra thời gian hiệu lực (12 giờ)
            if (Carbon::parse($passwordReset->created_at)->addHours(12)->isPast()) {
                $passwordReset->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Mã xác nhận đã hết hạn'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Mã xác nhận hợp lệ'
            ]);

        } catch (Exception $e) {
            Log::error('Error verifying code: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xác thực mã'
            ], 500);
        }
    }

    public function reset(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'code' => 'required|string|size:6',
                'password' => 'required|min:6',
                'password_confirmation' => 'required|same:password'
            ], [
                'email.required' => 'Email không được để trống',
                'email.email' => 'Email không đúng định dạng',
                'code.required' => 'Mã xác nhận không được để trống',
                'code.size' => 'Mã xác nhận phải có 6 ký tự',
                'password.required' => 'Vui lòng nhập mật khẩu mới',
                'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
                'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu',
                'password_confirmation.same' => 'Mật khẩu nhập lại không khớp'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first()
                ], 422);
            }

            $passwordReset = PasswordReset::where([
                ['email', $request->email],
                ['token', $request->code]
            ])->first();

            if (!$passwordReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mã xác nhận không hợp lệ'
                ], 400);
            }

            if (Carbon::parse($passwordReset->created_at)->addHours(12)->isPast()) {
                $passwordReset->delete();
                return response()->json([
                    'success' => false,
                    'message' => 'Mã xác nhận đã hết hạn'
                ], 400);
            }

            $user = User::where('email', $passwordReset->email)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy tài khoản'
                ], 404);
            }

            // Cập nhật mật khẩu
            $user->userpassword = md5($request->password);
            $user->save();

            // Xóa mã xác nhận sau khi đổi mật khẩu thành công
            $passwordReset->delete();

            // Gửi email thông báo đổi mật khẩu thành công
            try {
                Mail::send('emails.password-reset-success', [
                    'user' => $user
                ], function($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Đổi mật khẩu thành công - ' . config('app.name'));
                });
            } catch (Exception $e) {
                Log::warning('Failed to send password reset success email', [
                    'error' => $e->getMessage()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công'
            ]);

        } catch (Exception $e) {
            Log::error('Password reset failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt lại mật khẩu'
            ], 500);
        }
    }
}