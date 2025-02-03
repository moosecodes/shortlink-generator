<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Shortlink;
use App\Models\Location;
use App\Http\Controllers\Api\GetClicksOverTime;

class LinkGraphController extends Controller
{
    public function index(Request $request)
    {
        $id = $request->route('shortlink_id');

        $userId = $request->user()->id;

        $shortlink = Shortlink::where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        $data = $this->getClickData($shortlink);

        $graphData = $this->prepareGraphData($data);

        $locations = Location::where('user_id', $userId)->get();

        return Inertia::render('LinkAnalyticsPage', [
            'graphs' => $graphData,
            'locations' => $locations,
        ]);
    }

    private function getClickData($shortlink)
    {
        $clicksController = new GetClicksOverTime();
        $clickData = $clicksController->index($shortlink->id)->getData();
        $clickData->shortlink_id = $shortlink->id;
        $clickData->shortCode = $shortlink->short_code;

        return $clickData;
    }

    private function prepareGraphData($data)
    {
        $graphData = collect();
        $graphData->push([
            'shortlink_id' => $data->shortlink_id,
            'shortCode' => $data->shortCode,
            'labels' => $data->labels,
            'datasets' => [
                [
                    'label' => "Clicks ($data->shortCode)",
                    'backgroundColor' => '#f87979',
                    'borderColor' => '#fff',
                    'borderWidth' => 3,
                    'pointRadius' => 4,
                    'lineTension' => 0.1,
                    'data' => $data->datasets[0]->data,
                ]
            ],
        ]);

        return $graphData;
    }
}
