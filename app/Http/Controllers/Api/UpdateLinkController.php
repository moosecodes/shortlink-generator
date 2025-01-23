<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Shortlink;
use App\Models\Metadata;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UpdateLinkController extends Controller
{
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'id' => 'required|uuid',
            'user_url' => 'required|url',
            'metadatas' => 'array',
        ]);
    }

    public function index(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            // Find the shortlink
            $shortlink = Shortlink::where('id', $request->id)->firstOrFail();

            // Update the shortlink
            $shortlink->update($validatedData);

            // Update the shortlink metadata
            if (isset($validatedData['metadatas'])) {
                // Delete existing metadata
                $shortlink->metadatas()->delete();

                // Create new metadata
                foreach ($validatedData['metadatas'] as $key => $value) {
                    if (isset($value['meta_key']) && isset($value['meta_value'])) {
                        $shortlink->metadatas()->create([
                            'meta_key' => $validatedData['metadatas'][$key]['meta_key'],
                            'meta_value' => $validatedData['metadatas'][$key]['meta_value'],
                        ]);
                    }
                }
            }

            $metadatas = Metadata::where('shortlink_id', $shortlink->id)->get();

            return response()->json(array_merge(['shortlink' => $shortlink->toArray()], ['metadatas' => $metadatas->toArray()]));
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Shortlink not found: ' . $e->getMessage()], 404);
        } catch (Exception $e) {
            Log::error('Unexpected error occurred while updating shortlink: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
