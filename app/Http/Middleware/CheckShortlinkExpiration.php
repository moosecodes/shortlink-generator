<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Shortlink;

class CheckShortlinkExpiration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($shortCode = $request->route('short_code')) {
            if ($this->isShortlinkExpired($shortCode)) {
                return response()->json(['error' => 'This shortlink has expired!'], 410); // HTTP 410 Gone
            }
        }

        return $next($request);
    }

    private function isShortlinkExpired($shortCode)
    {
        $shortlink = Shortlink::where('short_code', $shortCode)->first();

        return !$shortlink || $shortlink->isExpired();
    }
}
