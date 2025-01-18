<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use Illuminate\Validation\ValidationException;

class CreateLinkController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'userId' => 'required|integer',
                'original_url' => 'required|url',
                'metadatas' => 'array',
            ]);

            // Generate a unique short code
            $shortCode = substr(hash_hmac('sha256', uniqid(), 'your_secret_key'), 0, 8);

            // Create the shortlink
            $shortlink = Shortlink::create(array_merge($validatedData, [
                'user_id' => $request->userId,
                'short_code' => $shortCode,
                'short_url' => config('app.url') . '/shortlinks/redirect/' . $shortCode,
                'is_premium' => true,
            ]));

            // Create the shortlink metadatas
            if (isset($validatedData['metadatas'])) {
                foreach ($validatedData['metadatas'] as $key => $value) {
                    if (isset($value['meta_key']) && isset($value['meta_value'])) {
                        $shortlink->metadatas()->create([
                            'meta_key' => $validatedData['metadatas'][$key]['meta_key'],
                            'meta_value' => $validatedData['metadatas'][$key]['meta_value'],
                        ]);
                    }
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

    public function freeLink(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'userId' => 'required|integer',
                'original_url' => 'required|url',
                'metadata_free' => 'array',
            ]);

            // Generate a unique short code
            $shortCode = substr(hash_hmac('sha256', uniqid(), 'your_free_key'), 0, 8);

            // Create the shortlink
            $shortlink = Shortlink::create(array_merge($validatedData, [
                'user_id' => $request->userId,
                'short_code' => $shortCode,
                'short_url' => config('app.url') . '/shortlinks/redirect/' . $shortCode,
                'is_active' => true,
                'is_premium' => false,
            ]));

            // Create the shortlink metadata
            if (
                isset($validatedData['metadata_free']) &&
                (isset($value['meta_key']) && $value['meta_key'] == "free") &&
                isset($value['meta_value'])
            ) {
                $shortlink->metadata()->create([
                    'meta_key' => $validatedData['metadata_free']['free']['meta_key'],
                    'meta_value' => $validatedData['metadata_free']['free']['meta_value'],
                ]);
            }

            return response()->json($shortlink, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while creating free shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
