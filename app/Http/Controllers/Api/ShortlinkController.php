<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ShortlinkController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            $shortCode = substr(hash_hmac('sha256', uniqid(), 'your_secret_key'), 0, 8);

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

    public function update(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);
            $shortlink = Shortlink::where('short_code', $request->id)->firstOrFail();

            $shortlink->update($validatedData);

            return response()->json($shortlink);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found'], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while updating shortlink: ' . $e->getMessage());
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

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'original_url' => 'required|url',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
            'geo_data' => 'nullable|json',
            'device_data' => 'nullable|json',
        ]);
    }
}
