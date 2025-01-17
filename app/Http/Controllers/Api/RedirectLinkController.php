<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use App\Models\Location;
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

            if (!$shortlink->is_active) return response()->json(['error' => 'Shortlink is not active!'], 400);

            $shortlink->increment('total_clicks');

            if (!$shortlink->uniqueClicks()->where('ip_address', $request->ip())->exists()) {
                $shortlink->uniqueClicks()->create([
                    'ip_address' => $request->ip(),
                    'device' => 'Unknown',
                    'browser' => 'Unknown',
                    'referrer' => $request->header('referer'),
                    'user_agent' => $request->header('User-Agent'),
                ]);

                // Create a new record in the locations table with default string values
                $location = Location::create([
                    'ip_address' => $request->ip(),
                    'driver' => 'default_driver',
                    'country_name' => 'default_country_name',
                    'country_code' => 'default_country_code',
                    'region_code' => 'default_region_code',
                    'region_name' => 'default_region_name',
                    'city_name' => 'default_city_name',
                    'zip_code' => 'default_zip_code',
                    'iso_code' => 'default_iso_code',
                    'postal_code' => 'default_postal_code',
                    'latitude' => 0.0000000,
                    'longitude' => 0.0000000,
                    'metro_code' => 'default_metro_code',
                    'area_code' => 'default_area_code',
                    'timezone' => 'default_timezone',
                ]);

                $shortlink->increment('unique_clicks');
            }

            $props = [];
            foreach ($shortlink->getAttributes() as $key => $value) {
                if ($key !== "short_code") {
                    $props[$key] = $value;
                }
            }

            return redirect($shortlink->original_url . '?' . http_build_query($props));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while redirecting: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
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
