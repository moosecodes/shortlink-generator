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

            // Sort the aggregated data by keys (dates)
            $sortedAggregatedData = $aggregatedData->sortKeys();

            $response = $this->prepareResponse($sortedAggregatedData);

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
        // Sort the aggregated data by keys (dates)
        $sortedAggregatedData = $aggregatedData->sortKeys();

        $labels = $sortedAggregatedData->keys()->toArray(); // Extract the dates as labels
        $data = $sortedAggregatedData->values()->toArray(); // Extract counts as data points

        return [
            'labels' => $labels,
            'datasets' => [
                [
                    'data' => $data,
                    'label' => 'Clicks Over Time',
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }
}
