<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use App\Models\Metadata;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ShowLinkController extends Controller
{

    public function index($id)
    {
        try {
            $shortlink = Shortlink::where('short_code', $id)->firstOrFail();
            $metadatas = Metadata::where('shortlink_id', $shortlink->id)->get();

            return response()->json(array_merge($shortlink->toArray(), ['metadatas' => $metadatas]));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while retrieving shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showAll()
    {
        $shortlinks = Shortlink::all();

        return response()->json($shortlinks);
    }

    public function showActive()
    {
        $shortlinks = Shortlink::where('is_active', true)->get();

        return response()->json($shortlinks);
    }

    public function showNotActive()
    {
        $shortlinks = Shortlink::where('is_active', false)->get();

        return response()->json($shortlinks);
    }
}
