<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\RedirectLinkController;
use App\Http\Controllers\Api\GetClicksOverTime;
use App\Models\Location;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Carbon\Carbon;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('landingPage');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function (Request $request) {
        $userId = $request->user()->id;

        $shortlinks = Shortlink::where('user_id', $userId)->get();

        // TODO: Add a check to see if the user has any shortlinks before proceeding
        $clicksController = new GetClicksOverTime();
        $graphs = collect();
        foreach ($shortlinks as $link) {
            $clickData = $clicksController->index($link->id)->getData();
            $clickData->shortlink_id = $link->id;
            $clickData->shortCode = $link->short_code;
            $graphs->push($clickData);
        }

        // Prepare configurations for each graph
        $graphData = collect();
        foreach ($graphs as $graph) {
            $graphData->push([
                'shortlink_id' => $graph->shortlink_id,
                'shortCode' => $graph->shortCode,
                'labels' => array_reverse($graph->labels),
                'datasets' => [
                    [
                        'label' => 'Clicks (' . $graph->shortCode . ')',
                        'backgroundColor' => '#fff',
                        'borderColor' => '#f87979',
                        'borderWidth' => 3,
                        'pointRadius' => 4,
                        'lineTension' => 0.2,
                        'data' => $graph->datasets[0]->data,
                    ]
                ],
            ]);
        }

        // Prepare location data
        $locations = Location::where('user_id', $userId)->get();

        return Inertia::render('Dashboard', [
            'graphs' => $graphData,
            'locations' => $locations,
        ]);
    })->name('dashboard');

    Route::get('/link/graphs/{shortlink_id}', function (Request $request, $id) {
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
    })->name('link.analytics');

    Route::get('/link/new', function () {
        return Inertia::render('NewLinkPage');
    })->name('NewLinkPage');

    Route::get('/link/update/{shortlink_id}', function ($shortlink_id) {
        return Inertia::render('UpdateShortlink', [
            'shortlink_id' => $shortlink_id,
        ]);
    });

    Route::get('/links/manage', function () {
        return Inertia::render('ManageLinksPage');
    })->name('show.links');
});

Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');
