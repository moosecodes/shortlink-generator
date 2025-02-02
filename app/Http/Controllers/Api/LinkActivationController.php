<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class LinkActivationController extends Controller
{
    public function activate($id)
    {
        return $this->updateStatus($id, 1, 'activated');
    }

    public function deactivate($id)
    {
        return $this->updateStatus($id, 0, 'deactivated');
    }

    private function updateStatus($id, $status, $action)
    {
        try {
            $shortlink = Shortlink::where('id', $id)->firstOrFail();
            $shortlink->update(['is_active' => $status]);

            return response()->json([
                'message' => "Shortlink {$action} successfully",
                'shortlink' => $shortlink
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error("Unexpected error occurred while {$action} shortlink: " . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
