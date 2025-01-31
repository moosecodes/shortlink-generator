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
            $clicks = $this->getClicksByShortlinkId($id);
            $aggregatedData = $this->aggregateClicksByDay($clicks);

            $response = $this->prepareResponse($aggregatedData);

            return response()->json($response);
        } catch (Exception $e) {
            Log::error('Error fetching clicks data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function getClicksByShortlinkId($id)
    {
        return ClicksOverTime::where('shortlink_id', $id)->get();
    }

    private function aggregateClicksByDay($clicks)
    {
        return $clicks->groupBy(function ($click) {
            return $click->created_at->format('Y-m-d');
        })->map(function ($group) {
            return $group->count();
        });
    }

    private function prepareResponse($aggregatedData)
    {
        $labels = $aggregatedData->keys()->toArray(); // Extract the dates as labels
        $data = $aggregatedData->values()->toArray(); // Extract counts as data points

        return [
            'labels' => $labels,
            'datasets' => [
                'labels' => $labels,
                'datasets' => [
                    [
                        'data' => $data,
                    ]
                ]
            ],
        ];
    }
}
