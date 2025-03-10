<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReactRouteMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reactRoutes = ['vat-pham', 'trang-chu', 'tin-tuc']; // Thêm các route React khác vào đây

        if (in_array($request->segment(2), $reactRoutes)) {
            return response()->view('kytrancac');
        }
        return $next($request);
    }
}
