<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use App\Models\ShortlinkMetadata;
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
            // Validate request
            $validatedData = $request->validate([
                'original_url' => 'required|url',
                'metadata' => 'array',
            ]);

            // Generate a unique short code
            $shortCode = substr(hash_hmac('sha256', uniqid(), 'your_secret_key'), 0, 8);

            // Create the shortlink
            $shortlink = Shortlink::create(array_merge($validatedData, [
                'short_code' => $shortCode
            ]));

            // Create the shortlink metadata
            if (isset($validatedData['metadata'])) {
                foreach ($validatedData['metadata'] as $key => $value) {
                    $shortlink->metadata()->create([
                        'meta_key' => $validatedData['metadata'][$key]['key'],
                        'meta_value' => $validatedData['metadata'][$key]['value'],
                    ]);
                }
            }

            return response()->json($shortlink, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while creating shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $shortlink = Shortlink::where('short_code', $id)->firstOrFail();
            $metadata = ShortlinkMetadata::where('shortlink_id', $shortlink->id)->get();

            return response()->json(array_merge($shortlink->toArray(), ['metadata' => $metadata]));
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

    public function update(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            // Find the shortlink
            $shortlink = Shortlink::where('id', $request->id)->firstOrFail();

            // Update the shortlink
            $shortlink->update($validatedData);

            // Update the shortlink metadata
            if (isset($validatedData['metadata'])) {
                // Delete existing metadata
                $shortlink->metadata()->delete();

                // Create new metadata
                foreach ($validatedData['metadata'] as $key => $value) {
                    $shortlink->metadata()->create([
                        'meta_key' => $validatedData['metadata'][$key]['meta_key'],
                        'meta_value' => $validatedData['metadata'][$key]['meta_value'],
                    ]);
                }
            }

            $metadata = ShortlinkMetadata::where('shortlink_id', $shortlink->id)->get();

            return response()->json(array_merge($shortlink->toArray(), ['metadata' => $metadata->toArray()]));
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found: ' . $e->getMessage()], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while updating shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'id' => 'required|uuid',
            'original_url' => 'required|url',
            'metadata' => 'array',
        ]);
    }
}
