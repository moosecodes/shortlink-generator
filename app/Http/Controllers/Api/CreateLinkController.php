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
                'original_url' => 'required|url',
                'metadata' => 'array',
            ]);

            // Generate a unique short code
            $shortCode = substr(hash_hmac('sha256', uniqid(), 'your_secret_key'), 0, 8);

            // Create the shortlink
            $shortlink = Shortlink::create(array_merge($validatedData, [
                'short_code' => $shortCode,
                'short_url' => config('app.url') . '/shortlinks/redirect/' . $shortCode,
            ]));

            // Create the shortlink metadata
            if (isset($validatedData['metadata'])) {
                foreach ($validatedData['metadata'] as $key => $value) {
                    if (isset($value['meta_key']) && isset($value['meta_value'])) {
                        $shortlink->metadata()->create([
                            'meta_key' => $validatedData['metadata'][$key]['meta_key'],
                            'meta_value' => $validatedData['metadata'][$key]['meta_value'],
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
}
