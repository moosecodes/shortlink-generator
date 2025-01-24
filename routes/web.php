<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\CheckShortlinkExpiration;
use App\Http\Controllers\Api\RedirectLinkController;
use App\Http\Controllers\Api\GetClicksOverTime;
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
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function (Request $request) {

        // Prepare graph data for each shortlink
        $userId = $request->user()->id;
        $shortlinks = Shortlink::where('user_id', $userId)->get();
        $clicksController = new GetClicksOverTime();
        $graphs = collect();
        foreach ($shortlinks as $link) {
            $clickData = $clicksController->index($link->id)->getData();
            $clickData->shortCode = $link->short_code;
            $graphs->push($clickData);
        }

        $graphData = collect();
        foreach ($graphs as $graph) {
            $graphData->push([
                'shortCode' => $graph->shortCode,
                'labels' => $graph->labels,
                'datasets' => [
                    [
                        'label' => 'Clicks Over Time (' . $graph->shortCode . ')',
                        'backgroundColor' => '#f87979',
                        'borderColor' => '#f87979',
                        'borderWidth' => 3,
                        'pointRadius' => 3,
                        'lineTension' => 0.2,
                        'data' => $graph->datasets[0]->data,
                    ]
                ],
            ]);
        }

        return Inertia::render('Dashboard', [
            'graphs' => $graphData,
        ]);
    })->name('dashboard');

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
    })->name('showAllShortlinks');
});

Route::get('/{short_code}', [RedirectLinkController::class, 'index'])
    ->middleware(CheckShortlinkExpiration::class)
    ->name('shortlink.redirect');
