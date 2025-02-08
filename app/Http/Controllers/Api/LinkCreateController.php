<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use Illuminate\Validation\ValidationException;
use App\Models\Metadata;

class LinkCreateController extends Controller
{
    public function index(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            $shortlink = $this->createShortlink($request);

            $this->createShortlinkMetadatas($request, $shortlink, $validatedData);

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
            'target_url' => 'required|url',
            'metadatas' => 'array',
            'custom_short_code' => 'string|nullable',
        ]);
    }

    private function generateShortCode(Request $request)
    {
        return $request->custom_short_code
            ?? substr(hash_hmac('sha256', uniqid(), config('app.secret_key')), 0, 8);
    }

    private function createShortlink(Request $request)
    {
        $userId = $request->user()->id ?? 999;

        $validatedData = $this->validateRequest($request);

        $hash = substr(hash_hmac('sha256', uniqid(), config('app.secret_key')), 0, 8);

        $shortCode = $this->generateShortCode($request, $hash);

        $data = [
            'title' => $request->title,
            'user_id' => $userId,
            'short_code' => $shortCode,
            'target_url' => $validatedData['target_url'],
            'short_url' => config('app.url') . '/' . $shortCode,
            'final_url' => 'http://www.bing.com/',
            'hash' => $hash,
            'is_active' => $request->user()->id === 999 ? true : false,
            'is_premium' => $request->user()->id === 999 ? false : true,
            'expires_at' => now()->addDays(30),
        ];

        $shortlink = Shortlink::create(array_merge($validatedData, $data));

        $props = collect();

        $metadatas = Metadata::where('shortlink_id', $shortlink->id);

        $props->put('user_id', $userId);

        $metadatas->each(function ($item, $key) use ($props) {
            $props->put($item->meta_key, $item->meta_value);
            $item->save();
        });

        $shortlink->final_url . '?' . http_build_query($props->toArray());

        $shortlink->save();

        return $shortlink;
    }

    private function createShortlinkMetadatas(Request $request, Shortlink $shortlink, array $validatedData)
    {
        if (isset($validatedData['metadatas'])) {
            foreach ($validatedData['metadatas'] as $metas) {
                if (isset($metas['meta_key']) && isset($metas['meta_value'])) {
                    $shortlink->metadatas()->create([
                        'user_id'
                        => $request->user()->id ?? 999,
                        'meta_key' => $metas['meta_key'],
                        'meta_value' =>
                        $metas['meta_value'],
                    ]);
                }
            }
        }
    }
}
