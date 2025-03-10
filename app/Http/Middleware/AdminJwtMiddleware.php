<?php
// app/Http/Middleware/AdminJwtMiddleware.php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Session;

class AdminJwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // Lấy token từ session
            $token = Session::get('admin_jwt_token');
            
            if (!$token) {
                return redirect('loginAdmin');
            }

            // Xác thực token
            $user = JWTAuth::setToken($token)->authenticate();
            
            if (!$user || $user->admin !== 1) {
                Session::forget('admin_jwt_token');
                return redirect('loginAdmin');
            }

        } catch (Exception $e) {
            Session::forget('admin_jwt_token');
            return redirect('loginAdmin');
        }

        return $next($request);
    }
}