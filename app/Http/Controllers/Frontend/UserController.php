<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Tbl_Member_Password;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Session;
use Cart;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB; // Thêm dòng này
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail; // nếu sử dụng Mail
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    private function verifyRecaptcha($token)
{
    try {
        if (empty($token)) {
            return [
                'success' => false,
                'error' => 'Token reCAPTCHA không được để trống'
            ];
        }

        $secretKey = config('services.recaptcha.secret_key');
        if (empty($secretKey)) {
            Log::error('reCAPTCHA secret key is not configured');
            return [
                'success' => false,
                'error' => 'Cấu hình reCAPTCHA không hợp lệ'
            ];
        }

        $response = Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => request()->ip()
        ]);

        if (!$response->successful()) {
            Log::error('reCAPTCHA API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return [
                'success' => false,
                'error' => 'Không thể kết nối đến dịch vụ reCAPTCHA'
            ];
        }

        $body = $response->json();
        
        Log::debug('reCAPTCHA response', [
            'success' => $body['success'] ?? false,
            'score' => $body['score'] ?? 0,
            'action' => $body['action'] ?? null,
            'hostname' => $body['hostname'] ?? null,
        ]);

        if (!($body['success'] ?? false)) {
            return [
                'success' => false,
                'error' => 'reCAPTCHA verification failed: ' . implode(', ', $body['error-codes'] ?? ['unknown error'])
            ];
        }

        if (($body['score'] ?? 0) < 0.5) {
            return [
                'success' => false,
                'error' => 'Điểm số reCAPTCHA quá thấp'
            ];
        }

        if (isset($body['action']) && $body['action'] !== 'register') {
            return [
                'success' => false,
                'error' => 'reCAPTCHA action không hợp lệ'
            ];
        }

        return [
            'success' => true,
            'score' => $body['score'] ?? 1.0
        ];

    } catch (\Exception $e) {
        Log::error('reCAPTCHA verification error', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return [
            'success' => false,
            'error' => 'Lỗi xác thực reCAPTCHA: ' . $e->getMessage()
        ];
    }
}
    public function register(Request $request)
    {
        $rules = [
            'u_name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'password_confirmation' => 'required|same:password'
        ];

        $validator = [
            'u_name.required' => 'Vui lòng nhập tài khoản',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password_confirmation.required' => 'Vui lòng nhập mật khẩu',
            'password_confirmation.same' => 'Mật khẩu nhập lại chưa đúng'
        ];

        Session::put('checkPopupRegister', 1);
        $this->validate($request, $rules, $validator);

        $checkEmail = User::where('email', $request->input('email'))->first();
        if ($checkEmail) {
            return redirect()->back()->with(['alert' => 'warning', 'message' => 'Email đã tồn tại']);
        }

        $checkDuplicate = Tbl_Member_Password::where('userid', $request->input('u_name'))->first();
        if ($checkDuplicate) {
            return redirect()->back()->with(['alert' => 'warning', 'message' => 'Tên tài khoản đã tồn tại']);
        }

        Session::forget('checkPopupRegister');

        $hashed_password = md5($request->input('password'));

        $user = new User();
        $user->userid = $request->input('u_name');
        $user->email = $request->input('email');
        $user->userpassword = $hashed_password;
        $user->save();

        Auth::login($user, true);

        $userPassword = new Tbl_Member_Password();
        $userPassword->userid = $request->input('u_name');
        $userPassword->userpassword = $hashed_password;
        $userPassword->save();

        return redirect()->back()->with(['alert' => 'success', 'message' => 'Tạo tài khoản và đăng nhập thành công']);
    }

    public function login(Request $request)
    {
        Cart::clear();

        $rules = [
            'u_name' => 'required',
            'password' => 'required'
        ];

        $validator = [
            'u_name.required' => 'Vui lòng nhập tài khoản',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ];

        Session::put('checkPopupLogin', 1);
        $this->validate($request, $rules, $validator);

        $user = User::where('userid', $request->u_name)
            ->where('userpassword', md5($request->password))
            ->first();

        if ($user) {
            Session::forget('checkPopupLogin');
            Auth::login($user, true);
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Đăng nhập thành công']);
        } else {
            return redirect()->back()->with(['alert' => 'warning', 'message' => 'Bạn đã nhập sai tài khoản hoặc mật khẩu']);
        }
    }

    public function loginHome(Request $request)
    {
        $rules = [
            'u_name' => 'required',
            'password' => 'required'
        ];

        $validator = [
            'u_name.required' => 'Vui lòng nhập tài khoản',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ];

        $this->validate($request, $rules, $validator);

        $user = User::where('userid', $request->u_name)
            ->where('userpassword', md5($request->password))
            ->first();

        if ($user) {
            Auth::login($user, true);
            return redirect()->back()->with(['alert' => 'success', 'message' => 'Đăng nhập thành công']);
        } else {
            return redirect()->back()->with(['alert' => 'warning', 'message' => 'Bạn đã nhập sai tài khoản hoặc mật khẩu']);
        }
    }

    public function backResetPass()
    {
        Session::forget('checkResetPass');
        return redirect()->back();
    }

    public function putResetPass()
    {
        Session::put('checkResetPass', 1);
        return redirect()->back();
    }

    public function resetPass(Request $request)
    {
        $rules = [
            'old_password' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password'
        ];
    
        $validator = [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 6 ký tự',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu mới',
            'password_confirmation.same' => 'Mật khẩu nhập lại chưa đúng'
        ];
    
        $validator = Validator::make($request->all(), $rules, $validator);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 422);
        }
    
        try {
            $user = Auth::guard('api')->user();
            
            // Kiểm tra mật khẩu cũ
            $old_password_hash = md5($request->old_password);
            if ($user->userpassword !== $old_password_hash) {
                return response()->json([
                    'error' => 'Mật khẩu cũ không chính xác'
                ], 422);
            }
    
            // Mã hóa mật khẩu mới
            $new_password_hash = md5($request->password);
    
            // Cập nhật mật khẩu trong cả hai bảng
            DB::beginTransaction();
            try {
                // Cập nhật trong bảng users
                User::where('id', $user->id)
                    ->update(['userpassword' => $new_password_hash]);
    
                // Cập nhật trong bảng Tbl_Member_Password
                Tbl_Member_Password::where('userid', $user->userid)
                    ->update(['userpassword' => $new_password_hash]);
    
                // Vô hiệu hóa token hiện tại
                JWTAuth::invalidate(JWTAuth::getToken());
    
                DB::commit();
    
                return response()->json([
                    'message' => 'Đổi mật khẩu thành công. Vui lòng đăng nhập lại.',
                    'logout' => true
                ], 200);
    
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
    
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Có lỗi xảy ra khi đổi mật khẩu'
            ], 500);
        }
    }
    
    public function logout(Request $request)
    {
        try {
            if ($request->bearerToken()) {
                JWTAuth::parseToken()->invalidate();
            }
            
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }
    }

    public function getCurrentUser(Request $request)
    {
        return response()->json([
            'user' => $request->user(),
            'token' => $request->bearerToken()
        ]);
    }
    
    public function getCoinBalance()
    {
        $user = Auth::guard('api')->user();
        return response()->json([
            'success' => true,
            'balance' => $user->coin
        ]);
    }
    
    public function getCurrentCoins(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'success' => true,
            'coins' => $user->coin
        ]);
    }
    
    

    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_name' => 'required|string',
            'password' => 'required|string',
        ], [
            'u_name.required' => 'Vui lòng nhập tài khoản',
            'password.required' => 'Vui lòng nhập mật khẩu'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }
    
        $u_name = $request->u_name;
        $password = md5($request->password);
    
        $user = User::where('userid', $u_name)
            ->where('userpassword', $password)
            ->first();
    
        if ($user) {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'access_token' => 'Bearer ' . $token, // Thêm prefix Bearer
                'token_type' => 'bearer',
                'expires_in' => JWTAuth::factory()->getTTL() * 60,
                'user' => $user
            ]);
        }
    
        return response()->json([
            'error' => 'Tài khoản hoặc mật khẩu không chính xác'
        ], 401);
    }

    public function apiRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_name' => 'required|unique:users,userid',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
            'recaptcha_token' => 'required' // Thêm validation cho token reCAPTCHA
        ], [
            'u_name.required' => 'Vui lòng nhập tài khoản',
            'u_name.unique' => 'Tên tài khoản đã tồn tại',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không đúng định dạng',
            'email.unique' => 'Email đã tồn tại',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password_confirmation.required' => 'Vui lòng nhập lại mật khẩu',
            'password_confirmation.same' => 'Mật khẩu nhập lại chưa đúng',
            'recaptcha_token.required' => 'Xác thực reCAPTCHA thất bại'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
    
        // Verify reCAPTCHA với logging
        $recaptchaResult = $this->verifyRecaptcha($request->recaptcha_token);
        if (!$recaptchaResult['success']) {
            Log::warning('reCAPTCHA verification failed', [
                'token' => substr($request->recaptcha_token, 0, 10) . '...',
                'error' => $recaptchaResult['error'] ?? 'Unknown error',
                'ip' => $request->ip()
            ]);
            return response()->json([
                'error' => $recaptchaResult['error'] ?? 'Xác thực reCAPTCHA thất bại'
            ], 400);
        }
    
        // Bắt đầu transaction trong MySQL
        DB::beginTransaction();
    
        try {
            // Tạo tài khoản trong MySQL
            $user = new User();
            $user->userid = $request->u_name;
            $user->email = $request->email;
            $user->userpassword = md5($request->password);
            $user->remember_token = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT); // Mã kích hoạt 6 số
            $user->save();
    
            // Commit MySQL transaction
            DB::commit();
    
            // Gọi stored procedure trên SQL Server
            DB::connection('sqlsrv')->statement('EXEC pr_Create_Account @member_id = ?, @member_pw = ?', [
                $request->u_name,
                $request->password
            ]);
    
            // Set member_class = 7 (chưa kích hoạt) trong SQL Server
            DB::connection('sqlsrv')->table('Tbl_ND_Member_Login')
                ->where('member_id', $request->u_name)
                ->update(['member_class' => 7]);
    
            // Gửi email kích hoạt
            try {
                Mail::send('emails.account-activation', [
                    'activation_code' => $user->remember_token,
                    'user' => $user,
                ], function($message) use ($user) {
                    $message->to($user->email)
                            ->subject('Mã kích hoạt tài khoản của bạn');
                });
            } catch (\Exception $e) {
                Log::error('Failed to send activation email', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->userid,
                    'email' => $user->email
                ]);
            }
    
            return response()->json([
                'alert' => 'success',
                'message' => 'Tạo tài khoản thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.'
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Registration error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'alert' => 'error',
                'message' => 'Có lỗi xảy ra khi tạo tài khoản. Vui lòng thử lại sau.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

protected function respondWithToken($token, $user)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 60,
        'user' => $user
    ]);
}
public function refresh()
{
    try {
        $token = JWTAuth::parseToken()->refresh();
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Không thể refresh token'], 401);
    }
}

public function sendActivationMail(Request $request)
{
    $user = User::where('email', $request->email)->first();

    if (!$user || $user->email_verified_at) {
        return response()->json([
            'alert' => 'warning',
            'message' => 'Email này đã được sử dụng hoặc tài khoản đã được kích hoạt'
        ], 400);
    }

    // Tạo mã kích hoạt 6 số
    $activation_code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    
    // Lưu mã kích hoạt vào remember_token
    $user->remember_token = $activation_code;
    $user->save();

    // Gửi email kích hoạt
    Mail::send('emails.account-activation', [
        'activation_code' => $activation_code,
        'user' => $user,
    ], function($message) use ($user) {
        $message->to($user->email)
                ->subject('Mã kích hoạt tài khoản của bạn');
    });

    return response()->json([
        'alert' => 'success',
        'message' => 'Đã gửi mã kích hoạt. Vui lòng kiểm tra hộp thư của bạn.'
    ]);
}

public function activateAccount($token)
{
    $user = User::where('remember_token', $token)->first();

    if (!$user) {
        return response()->json([
            'alert' => 'error',
            'message' => 'Token kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt'
        ], 400);
    }

    $user->email_verified_at = now(); // Đánh dấu tài khoản đã được kích hoạt
    $user->remember_token = null;     // Xóa token kích hoạt để tránh tái sử dụng
    $user->save();

    return response()->json([
        'alert' => 'success',
        'message' => 'Tài khoản của bạn đã được kích hoạt thành công!'
    ]);
}
public function activateAccountAPI($token)
{
    // Lấy người dùng từ bảng MySQL `users` bằng token
    $user = User::where('remember_token', $token)->first();

    if (!$user) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Token kích hoạt không hợp lệ hoặc tài khoản đã được kích hoạt.'
        ], 400);
    }

    // Kết nối tới SQL Server và kiểm tra trạng thái kích hoạt trong `Tbl_ND_Member_Login`
    $member = DB::connection('sqlsrv')->table('Tbl_ND_Member_Login')
        ->where('member_id', $user->userid)
        ->first();

    if (!$member || $member->member_class != 7) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Tài khoản đã được kích hoạt hoặc không tồn tại.'
        ], 400);
    }

    // Cập nhật trạng thái kích hoạt: đặt `member_class` thành 0
    DB::connection('sqlsrv')->table('Tbl_ND_Member_Login')
        ->where('member_id', $user->userid)
        ->update(['member_class' => 0]);

    // Xóa `remember_token` trong MySQL để đánh dấu đã kích hoạt
    $user->remember_token = null;
    $user->save();

    return response()->json([
        'status' => 'success',
        'message' => 'Tài khoản đã được kích hoạt thành công!'
    ], 200);
}

public function checkActivationStatus($email)
{
    try {
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            return response()->json([
                'error' => 'Không tìm thấy người dùng',
                'isActivated' => false
            ], 404);
        }

        // Kiểm tra trạng thái kích hoạt từ SQL Server
        $member = DB::connection('sqlsrv')->table('Tbl_ND_Member_Login')
            ->where('member_id', $user->userid)
            ->first();

        $isActivated = $member && $member->member_class == 0;

        return response()->json([
            'isActivated' => $isActivated,
            'message' => $isActivated ? 'Tài khoản đã được kích hoạt' : 'Tài khoản chưa được kích hoạt'
        ]);
    } catch (\Exception $e) {
        \Log::error('Lỗi kiểm tra trạng thái kích hoạt: ' . $e->getMessage());
        return response()->json([
            'error' => 'Có lỗi xảy ra khi kiểm tra trạng thái kích hoạt',
            'isActivated' => false
        ], 500);
    }
}
// Thêm hàm verify-activation-code
public function verifyActivationCode(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'activation_code' => 'required|string|size:6'
    ], [
        'email.required' => 'Email không được để trống',
        'email.email' => 'Email không đúng định dạng',
        'activation_code.required' => 'Mã kích hoạt không được để trống',
        'activation_code.size' => 'Mã kích hoạt phải có 6 ký tự'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }

    try {
        DB::beginTransaction();

        // Tìm user với email và mã kích hoạt tương ứng
        $user = User::where('email', $request->email)
                   ->where('remember_token', $request->activation_code)
                   ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Mã kích hoạt không chính xác'
            ], 400);
        }

        // Kiểm tra trạng thái kích hoạt trong SQL Server
        $member = DB::connection('sqlsrv')->table('Tbl_ND_Member_Login')
            ->where('member_id', $user->userid)
            ->first();

        if ($member && $member->member_class == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Tài khoản đã được kích hoạt trước đó'
            ], 400);
        }

        // Cập nhật trạng thái trong MySQL
        $user->remember_token = null; // Xóa mã kích hoạt
        $user->save();

        // Cập nhật trạng thái trong SQL Server
        DB::connection('sqlsrv')->table('Tbl_ND_Member_Login')
            ->where('member_id', $user->userid)
            ->update(['member_class' => 0]); // 0 là trạng thái đã kích hoạt

        DB::commit();

        // Tạo token JWT cho đăng nhập tự động
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Kích hoạt tài khoản thành công!',
            'access_token' => 'Bearer ' . $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => $user
        ]);

    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Lỗi kích hoạt tài khoản: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Có lỗi xảy ra trong quá trình kích hoạt tài khoản'
        ], 500);
    }
}

}


