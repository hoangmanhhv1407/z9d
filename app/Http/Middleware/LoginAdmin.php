<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class LoginAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::guard('web')->check()) {
            if (Auth::guard('web')->user()->admin == 1) {
                return $next($request);
            } else {
                return redirect('/loginAdmin');
            }
        } else {
            return redirect('/loginAdmin');
        }
    }
}
