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

    public function index(Request $request)
    {
        try {
            $shortlink = $this->findShortlink($request->userId, $request->id);
            $metadatas = $this->getMetadatas($shortlink->id);

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

    private function findShortlink($userId, $shortCode)
    {
        return Shortlink::where('user_id', $userId)
            ->where('short_code', $shortCode)
            ->firstOrFail();
    }

    private function getMetadatas($shortlinkId)
    {
        return Metadata::where('shortlink_id', $shortlinkId)->get();
    }
}
