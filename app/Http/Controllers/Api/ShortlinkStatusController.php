<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;
use Illuminate\Support\Facades\Log;

class ShortlinkStatusController extends Controller
{
    public function activate($id)
    {
        try {
            $shortlink = Shortlink::where('short_code', $id)->firstOrFail();
            $shortlink->update(['is_active' => true]);

            return response()->json(['message' => 'Shortlink activated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while activating shortlink: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function deactivate($id)
    {
        try {
            $shortlink = Shortlink::where('short_code', $id)->firstOrFail();
            $shortlink->update(['is_active' => false]);

            return response()->json(['message' => 'Shortlink deactivated successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while deactivating shortlink: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
