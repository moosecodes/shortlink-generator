<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\Location;
use Inertia\Inertia;
use App\Http\Controllers\Api\GetClicksOverTime;

class LinkGraphController extends Controller
{
    public function index(Request $request, $id)
    {
        $userId = $request->user()->id;

        $shortlink = Shortlink::where('user_id', $userId)->where('id', $id)->get();
        // TODO: Add a check to see if the user has any shortlinks before proceeding
        $clicksController = new GetClicksOverTime();

        $clickData = $clicksController->index($shortlink[0]->id)->getData();
        $clickData->shortlink_id = $request->route('shortlink_id');
        $clickData->shortCode = $request->route('shortlink_id');

        // Prepare configurations for each graph
        $graphData = collect();
        $graphData->push([
            'shortlink_id' => $clickData->shortlink_id,
            'shortCode' => $clickData->shortCode,
            'labels' => array_reverse($clickData->labels),
            'datasets' => [
                [
                    'label' => 'Clicks (' . $clickData->shortCode . ')',
                    'backgroundColor' => '#fff',
                    'borderColor' => '#f87979',
                    'borderWidth' => 3,
                    'pointRadius' => 4,
                    'lineTension' => 0.2,
                    'data' => $clickData->datasets[0]->data,
                ]
            ],
        ]);

        // Prepare location data
        $locations = Location::where('user_id', $userId)->get();

        return Inertia::render('LinkAnalytics', [
            'graphs' => $graphData,
            'locations' => $locations,
        ]);
    }
}
