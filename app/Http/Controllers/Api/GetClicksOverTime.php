<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ClicksOverTime;
use Exception;
use Illuminate\Support\Facades\Log;

class GetClicksOverTime extends Controller
{
    public function index($id)
    {
        try {
            $clicks = ClicksOverTime::where('shortlink_id', $id)->get();

            // Aggregate clicks by day
            $aggregatedData = $clicks->groupBy(function ($click) {
                return $click->created_at->format('Y-m-d');
            })->map(function ($group) {
                return $group->count();
            });

            // Prepare labels and dataset
            $labels = $aggregatedData->keys()->toArray(); // Extract the dates as labels
            $data = $aggregatedData->values()->toArray(); // Extract counts as data points

            $response = [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                    ]
                ]
            ];

            return response()->json($response);
        } catch (Exception $e) {
            Log::error('Error fetching clicks data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
