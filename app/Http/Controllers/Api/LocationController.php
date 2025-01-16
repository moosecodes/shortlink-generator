<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class LocationController extends Controller
{
    public function index($ip)
    {
        try {
            $location = Location::get($ip);
            return response()->json($location);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Location not found'], 404);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
