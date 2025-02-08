<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AutoLoginGuest
{
    public function handle(Request $request, Closure $next)
    {
        // if (!Auth::check()) {
        //     Auth::loginUsingId(999); // Automatically log in guest user
        // }

        // // Ensure `user_id` is added to request parameters
        // $modifiedRequest = $request->replace([
        //     'user_id' => Auth::id(),
        // ] + $request->all()); // Keeps all original parameters

        return $next($request);
    }
}
