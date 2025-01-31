<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AutoLoginGuest
{
    public function handle(Request $request, Closure $next)
    {

        // dd($request->user());
        // if (!Auth::check()) {
        //     $user = User::find(999);

        //     if ($user) {
        //         Auth::login($user);
        //     }
        // }

        return $next($request);
    }
}
