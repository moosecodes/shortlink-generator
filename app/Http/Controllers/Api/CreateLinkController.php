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

            $userId = $request->user()->id ?? 999;

            $hash = substr(hash_hmac('sha256', uniqid(), config('app.secret_key')), 0, 8);

            $validatedData = $this->validateRequest($request);

            $shortCode = $this->generateShortCode($request, $hash);

            $shortlink = $this->createShortlink($validatedData, $userId, $shortCode, $hash);

            $this->createShortlinkMetadatas($shortlink, $validatedData);

            return response()->json($shortlink, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (Exception $e) {
            Log::error('500 Unexpected error occurred while creating shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'userId' => 'integer',
            'user_url' => 'required|url',
            'metadatas' => 'array',
            'custom_short_code' => 'string|nullable',
        ]);
    }

    private function generateShortCode(Request $request, $hash)
    {
        return $request->custom_short_code ?? substr(hash_hmac('sha256', uniqid(), config('app.secret_key')), 0, 8);
    }

    private function createShortlink(array $validatedData, $userId, $shortCode, $hash)
    {
        $data = [
            'user_id' => $userId,
            'user_url' => $validatedData['user_url'],
            'short_code' => $shortCode,
            'short_url' => config('app.url') . '/' . $shortCode,
            'hash' => $hash,
            'is_active' => $userId === 999 ? true : false,
            'expires_at' => now()->addDays(30),
        ];

        return Shortlink::create(array_merge($validatedData, $data));
    }

    private function createShortlinkMetadatas(Shortlink $shortlink, array $validatedData)
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
}
