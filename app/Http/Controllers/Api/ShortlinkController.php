<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShortlinkController extends Controller
{
    public function index()
    {
        $shortlinks = Shortlink::all();

        return response()->json($shortlinks);
    }

    public function redirect($short_code)
    {
        try {
            $shortlink = Shortlink::where('short_code', $short_code)->firstOrFail();

            if ($shortlink->is_active) {
                $shortlink->increment('total_clicks');
                return redirect($shortlink->original_url);
            } else {
                return response()->json(['error' => 'Shortlink is not active'], 400);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while redirecting: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $shortlink = Shortlink::where('short_code', $id)->firstOrFail();

            return response()->json($shortlink);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while retrieving shortlink: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    public function showActive()
    {
        $shortlinks = Shortlink::where('is_active', true)->get();

        return response()->json($shortlinks);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            $shortCode = Str::random(6);

            $shortlink = Shortlink::create(array_merge($validatedData, [
                'short_code' => $shortCode,
            ]));

            return response()->json($shortlink, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while creating shortlink: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'original_url' => 'required|url',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
        ]);
    }

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

    public function delete($id)
    {
        try {
            $shortlink = Shortlink::where('short_code', $id)->firstOrFail();
            $shortlink->delete();

            return response()->json(['message' => 'Shortlink deleted successfully']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while deleting shortlink: ' . $e->getMessage());
            return response()->json(['error' => 'An unexpected error occurred.'], 500);
        }
    }
}
