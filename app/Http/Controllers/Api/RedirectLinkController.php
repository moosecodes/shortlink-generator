<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RedirectLinkController extends Controller
{
    public function index($short_code, Request $request)
    {
        try {
            $shortlink = Shortlink::where('short_code', $short_code)->firstOrFail();

            if (!$shortlink->is_active) return response()->json(['error' => 'Shortlink is not active'], 400);

            $shortlink->increment('total_clicks');

            // Track unique clicks by IP address
            $ipAddress = $request->ip();
            $device = $request->header('User-Agent');
            $browser = $request->header('User-Agent');
            $country = 'Unknown';
            $referrer = $request->header('referer');
            $userAgent = $request->header('User-Agent');

            if (!$shortlink->uniqueClicks()->where('ip_address', $ipAddress)->exists()) {
                $shortlink->uniqueClicks()->create([
                    'ip_address' => $ipAddress,
                    'device' => $device,
                    'browser' => $browser,
                    'country' => $country,
                    'referrer' => $referrer,
                    'user_agent' => $userAgent,
                ]);
                $shortlink->increment('unique_clicks');
            }

            $props = [];
            foreach ($shortlink->getAttributes() as $key => $value) {
                if ($key == "short_code") {
                    $props[$key] = $value;
                }
            }

            return redirect($shortlink->original_url . '?' . http_build_query($props));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while redirecting: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function getUrls(Request $request)
    {
        $shortlinks = Shortlink::all();
        $urls = [];
        foreach ($shortlinks as $shortlink) {
            $props = [];
            foreach ($shortlink->getAttributes() as $key => $value) {
                if ($key == "short_code") {
                    $props[$key] = $value;
                }
            }
            $urls[] = [
                'id' => $shortlink->id,
                'short_code' => $shortlink->short_code,
                'url' => $shortlink->original_url . '?' . http_build_query($props),
                'short_url' => $shortlink->short_url,
            ];
        }

        return response()->json(['shortlink_redirect_urls' => $urls]);
    }
}
