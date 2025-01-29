<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Shortlink;
use App\Models\Location;
use App\Http\Controllers\Api\GetClicksOverTime;

class LinkGraphController extends Controller
{
    public function index(Request $request, $shortlink_id)
    {
        $userId = $request->user()->id;

        $shortlink = Shortlink::where('user_id', $userId)->where('id', $shortlink_id)->firstOrFail();
        $clickData = $this->getClickData($shortlink->id, $request->route('shortlink_id'));

        $graphData = $this->prepareGraphData($clickData);
        $locations = Location::where('user_id', $userId)->get();

        return Inertia::render('LinkAnalytics', [
            'graphs' => $graphData,
            'locations' => $locations,
        ]);
    }

    private function getClickData($shortlinkId, $shortlinkRouteId)
    {
        $clicksController = new GetClicksOverTime();
        $clickData = $clicksController->index($shortlinkId)->getData();
        $clickData->shortlink_id = $shortlinkRouteId;
        $clickData->shortCode = $shortlinkRouteId;

        return $clickData;
    }

    private function prepareGraphData($clickData)
    {
        $graphData = collect();
        $graphData->push([
            'shortlink_id' => $clickData->shortlink_id,
            'shortCode' => $clickData->shortCode,
            'labels' => array_reverse($clickData->labels),
            'datasets' => [
                [
                    'label' => "Clicks (' . $clickData->shortCode . ')",
                    'backgroundColor' => '#fff',
                    'borderColor' => '#f87979',
                    'borderWidth' => 3,
                    'pointRadius' => 4,
                    'lineTension' => 0.2,
                    'data' => $clickData->datasets[0]->data,
                ]
            ],
        ]);

        return $graphData;
    }
}
