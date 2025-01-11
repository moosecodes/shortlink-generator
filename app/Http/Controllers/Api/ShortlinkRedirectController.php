<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class ShortlinkRedirectController extends Controller
{
    public function index($short_code, Request $request)
    {
        try {
            $shortlink = Shortlink::where('short_code', $short_code)->firstOrFail();

            if ($shortlink->is_active) {
                $shortlink->increment('total_clicks');

                // Track unique clicks by IP address
                $ipAddress = $request->ip();
                if (!$shortlink->uniqueClicks()->where('ip_address', $ipAddress)->exists()) {
                    $shortlink->uniqueClicks()->create(['ip_address' => $ipAddress]);
                    $shortlink->increment('unique_clicks');
                }

                return redirect($shortlink->original_url);
            } else {
                return response()->json(['error' => 'Shortlink is not active'], 400);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while redirecting: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
