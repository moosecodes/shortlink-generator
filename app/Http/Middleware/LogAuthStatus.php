<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; // ✅ Explicitly import Auth facade

class LogAuthStatus
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user(); // ✅ Use Auth::user() instead of auth()->user()

        Log::info('Authenticated user: ' . ($user ? $user->id : 'Guest'));

        return $next($request);
    }
}
