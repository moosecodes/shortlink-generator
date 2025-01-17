<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Location;
use Illuminate\Validation\ValidationException;

class CreateLocationController extends Controller
{
    public function index($data, Request $request)
    {
        try {
            // Create the location
            $location = Location::create([
                'ip_address' => $data['ip_address'],
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

            return response()->json($location, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while creating shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
