<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogAuthStatus
{
    public function handle(Request $request, Closure $next)
    {
        Log::info('User authenticated: ' . auth()->check());
        Log::info('Authenticated user: ' . auth()->user());
        return $next($request);
    }
}
