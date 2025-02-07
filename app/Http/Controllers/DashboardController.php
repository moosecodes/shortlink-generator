<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\Location;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $shortlinks = Shortlink::where('user_id', $userId)->get();

        $locations = Location::where('user_id', $userId)->get();

        return Inertia::render('DashboardPage', [
            'title' => 'Dashboard',
            'shortlinks' => $shortlinks,
            'locations' => $locations,
        ]);
    }
}
