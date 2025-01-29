<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class DeleteLinkController extends Controller
{
    public function index($id)
    {
        try {
            $shortlink = $this->findShortlinkById($id);
            $shortlink->delete();

            return response()->json(['message' => 'Shortlink deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while deleting shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function findShortlinkById($id)
    {
        return Shortlink::where('short_code', $id)->firstOrFail();
    }
}
