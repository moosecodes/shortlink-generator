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
            $validatedData = $this->validateRequest($request);

            $shortCode = $this->generateShortCode($request);

            $shortlink = $this->createShortlink($validatedData, $request->userId, $shortCode);

            $this->createShortlinkMetadatas($shortlink, $validatedData);

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
            'userId' => 'required|integer',
            'user_url' => 'required|url',
            'metadatas' => 'array',
            'custom_short_code' => 'string|nullable',
        ]);
    }

    private function generateShortCode(Request $request)
    {
        return isset($request->custom_short_code)
            ? $request->custom_short_code
            : substr(hash_hmac('sha256', uniqid(), 'your_secret_key'), 0, 8);
    }

    private function createShortlink(array $validatedData, $userId, $shortCode)
    {
        return Shortlink::create(array_merge($validatedData, [
            'user_id' => $userId,
            'short_code' => $shortCode,
            'short_url' => config('app.url') . '/' . $shortCode,
            'is_active' => false,
            'is_premium' => true,
            'expires_at' => now()->addDays(30),
        ]));
    }

    private function createShortlinkMetadatas($shortlink, array $validatedData)
    {
        if (isset($validatedData['metadatas'])) {
            foreach ($validatedData['metadatas'] as $metadata) {
                if (isset($metadata['meta_key']) && isset($metadata['meta_value'])) {
                    $shortlink->metadatas()->create([
                        'meta_key' => $metadata['meta_key'],
                        'meta_value' => $metadata['meta_value'],
                    ]);
                }
            }
        }
    }

    public function freeLink(Request $request)
    {
        try {
            // Validate request
            $validatedData = $request->validate([
                'user_id' => 'required|integer',
                'user_url' => 'required|url',
                'metadata_free' => 'array',
            ]);

            // Generate a unique short code
            $shortCode = substr(hash_hmac('sha256', uniqid(), 'your_free_key'), 0, 8);

            // Create the shortlink
            $shortlink = Shortlink::create(array_merge($validatedData, [
                'user_id' => $request->user_id,
                'short_code' => $shortCode,
                'short_url' => config('app.url') . '/' . $shortCode,
                'is_active' => true,
                'is_premium' => false,
                'expires_at' => now()->addDays(30),
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
