<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\Location;
use App\Http\Controllers\Api\GetClicksOverTime;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $shortlinks = Shortlink::where('user_id', $userId)->get();
        $graphData = $this->prepareGraphData($shortlinks);

        $locations = Location::where('user_id', $userId)->get();

        return Inertia::render('Dashboard', [
            'graphs' => $graphData,
            'locations' => $locations,
        ]);
    }

    private function prepareGraphData($shortlinks)
    {
        $clicksController = new GetClicksOverTime();
        $graphs = collect();

        foreach ($shortlinks as $link) {
            $clickData = $clicksController->index($link->id)->getData();
            $clickData->shortlink_id = $link->id;
            $clickData->shortCode = $link->short_code;
            $graphs->push($clickData);
        }

        $graphData = collect();
        foreach ($graphs as $graph) {
            $graphData->push([
                'shortlink_id' => $graph->shortlink_id,
                'shortCode' => $graph->shortCode,
                'labels' => array_reverse($graph->labels),
                'datasets' => [
                    [
                        'label' => "Clicks ({$graph->shortCode})",
                        'backgroundColor' => '#fff',
                        'borderColor' => '#f87979',
                        'borderWidth' => 3,
                        'pointRadius' => 4,
                        'lineTension' => 0.2,
                        'data' => $graph->datasets->datasets[0]->data,
                    ]
                ],
            ]);
        }

        return $graphData;
    }
}
