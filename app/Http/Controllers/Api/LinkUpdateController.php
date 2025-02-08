<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Metadata;
use App\Models\Shortlink;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class LinkUpdateController extends Controller
{
    public function update(Request $request)
    {
        try {
            $validatedData = $this->validateRequest($request);

            $shortlink = $this->findShortlink($request->id);

            $this->updateShortlink($shortlink, $validatedData);

            $this->updateShortlinkMetadata($shortlink, $validatedData);

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

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'id' => 'required|exists:shortlinks,id',
            'short_code' => 'required|string',
            'target_url' => 'required|url',
            'metadatas' => 'array',
            'metadatas.*.meta_key' => 'required_with:metadatas|string',
            'metadatas.*.meta_value' => 'required_with:metadatas|string',
        ]);
    }

    private function findShortlink($id)
    {
        return Shortlink::where('id', $id)->firstOrFail();
    }

    private function updateShortlink($shortlink, $validatedData)
    {
        $shortlink->update($validatedData);
    }

    private function updateShortlinkMetadata($shortlink, $validatedData)
    {
        if (isset($validatedData['metadatas'])) {
            $shortlink->metadatas()->delete();

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
