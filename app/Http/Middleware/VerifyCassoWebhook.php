<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyCassoWebhook
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('Secure-Token') !== config('services.casso.secret_key')) {
            return response()->json(['error' => 'Invalid secure token'], 401);
        }

        return $next($request);
    }
}