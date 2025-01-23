<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use App\Models\Location;
use Stevebauman\Location\Facades\Location as LocationService;
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
            $shortlink->clicksOverTime()->create([
                'ip_address' => $request->ip(),
                'referrer' => $request->header('referer'),
                'clicked_at' => now(),
            ]);

            $ipAddress = $request->ip();

            $location = LocationService::get('98.220.20.182');

            if (!$shortlink->uniqueClicks()->where('ip_address', $ipAddress)->exists()) {
                $shortlink->uniqueClicks()->create([
                    'ip_address' => $ipAddress,
                    'device' => 'Unknown',
                    'browser' => 'Unknown',
                    'referrer' => $request->header('referer'),
                    'user_agent' => $request->header('User-Agent'),
                ]);

                // Create a new record in the locations table with default string values
                $location = Location::create([
                    'ip_address' => $ipAddress,
                    'driver' => $location->driver,
                    'country_name' => $location->countryName,
                    'country_code' => $location->countryCode,
                    'region_code' => $location->regionCode,
                    'region_name' => $location->regionName,
                    'city_name' => $location->cityName,
                    'zip_code' => $location->zipCode,
                    'iso_code' => $location->isoCode,
                    'postal_code' => $location->postalCode,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'metro_code' => $location->metroCode,
                    'area_code' => $location->areaCode,
                    'timezone' => $location->timezone,
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

    public function getRedirects(Request $request)
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
