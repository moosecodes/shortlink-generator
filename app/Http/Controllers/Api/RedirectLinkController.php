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
    public function index(Request $request, $short_code)
    {
        try {
            $shortlink = Shortlink::where('short_code', $short_code)->firstOrFail();

            $userId = $request->user()->id ?? 999;

            if (!$shortlink->is_active) return response()->json(['error' => 'Shortlink ' . $short_code . 'is not active!'], 400);

            $this->recordClick($shortlink, $request);

            if (env('APP_ENV') === 'production') {
                $ipAddress = $request->ip();
            } else {
                $ipAddress = $this->getRandomIpAddress();
            }

            $this->upsertUniqueClick($shortlink, $ipAddress, $userId, $request);

            $props = [];

            return redirect($shortlink->user_url . '?' . http_build_query($props));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while redirecting: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred: ' . $e->getMessage()], 500);
        }
    }

    private function recordClick(Shortlink $shortlink, Request $request)
    {

        if ($request->has('qr') && $request->input('qr') == 1) {
            $shortlink->increment('qr_scans');
        } else {
            $shortlink->increment('total_clicks');
        }

        $shortlink->clicksOverTime()->create([
            'ip_address' => $request->ip(),
            'referrer' => $request->header('referer'),
            'clicked_at' => now(),
        ]);
    }

    private function getRandomIpAddress()
    {
        $ipAddresses = [
            '98.220.20.182',
            '77.73.203.21',
            '67.199.248.14',
            '163.195.1.225',
            '151.101.192.144',
            '184.30.42.22',
            '47.246.136.156',
        ];

        $ip = $ipAddresses[array_rand($ipAddresses)];

        Log::info('Selected IP Address: ' . $ip);

        return $ip;
    }

    private function upsertUniqueClick(Shortlink $shortlink, $ipAddress, $userId, Request $request)
    {
        if (!$shortlink->uniqueClicks()->where('ip_address', $ipAddress)->exists()) {
            $shortlink->uniqueClicks()->create([
                'ip_address' => $ipAddress,
                'device' => 'Unknown',
                'browser' => 'Unknown',
                'referrer' => $request->header('referer'),
                'user_agent' => $request->header('User-Agent'),
            ]);

            $shortlink->increment('unique_clicks');

            $this->insertLocationData($userId, $ipAddress);
        }
    }

    private function insertLocationData($userId, $ipAddress)
    {
        $location = LocationService::get($ipAddress);

        // Create a new record in the locations table with default string values
        $location = Location::create([
            'user_id' => $userId,
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
    }
}
