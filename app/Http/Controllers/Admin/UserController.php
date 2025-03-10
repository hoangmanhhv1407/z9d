<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Auth;

class UserController extends Controller
{
    public function getLogin()
    {
        if (Auth::guard('web')->check() && Auth::guard('web')->user()->admin == 1) {
            return redirect('/admins');
        }
        return view('admin.user.login');
    }

    public function postLogin(Request $request)
    {
        $rules = [
            'user' => 'required',
            'password' => 'required'
        ];

        $messages = [
            'user.required' => 'User không được để trống',
            'password.required' => 'Password không được để trống'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                   ->withErrors($validator)
                   ->withInput($request->except('password'));
        }

        $user = User::where('userid', $request->user)
                    ->where('userpassword', md5($request->password))
                    ->first();

        if ($user) {
            if ($user->admin !== 1) {
                $errors = new MessageBag(['errorlogin' => 'Tài khoản không có quyền admin']);
                return redirect()->back()
                       ->withInput($request->except('password'))
                       ->withErrors($errors);
            }
        // Đăng nhập bằng guard 'web'
        Auth::guard('web')->login($user, true);
            return redirect()->intended('/admins');
        }

        $errors = new MessageBag(['errorlogin' => 'Tài khoản hoặc mật khẩu không đúng']);
        return redirect()->back()
               ->withInput($request->except('password'))
               ->withErrors($errors);
    }

    public function getLogout()
    {
        Auth::guard('web')->logout();
        return redirect('/loginAdmin');
    }
}