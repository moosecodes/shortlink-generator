<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use App\Models\Metadata;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ShowLinkController extends Controller
{

    public function index($id, Request $request)
    {
        try {
            $shortlink = Shortlink::where('user_id', $request->userId)
                ->where('short_code', $id)
                ->firstOrFail();
            $metadatas = Metadata::where('shortlink_id', $shortlink->id)->get();

            return response()->json(array_merge($shortlink->toArray(), ['metadatas' => $metadatas]));
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while retrieving shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function showAll(Request $request)
    {
        $shortlinks = Shortlink::where('user_id', $request->userId)->get();
        return response()->json($shortlinks);
    }

    public function showActive(Request $request)
    {
        $shortlinks = Shortlink::where('user_id', $request->userId)
            ->where('is_active', true)
            ->get();
        return response()->json($shortlinks);
    }

    public function showNotActive()
    {
        $shortlinks = Shortlink::where('is_active', false)->get();

        return response()->json($shortlinks);
    }
}
