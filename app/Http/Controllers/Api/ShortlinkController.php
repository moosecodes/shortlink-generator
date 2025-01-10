<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shortlink;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShortlinkController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'original_url' => 'required|url',
            'utm_source' => 'nullable|string|max:255',
            'utm_medium' => 'nullable|string|max:255',
            'utm_campaign' => 'nullable|string|max:255',
            'utm_term' => 'nullable|string|max:255',
            'utm_content' => 'nullable|string|max:255',
        ]);

        $shortCode = Str::random(6);

        $shortlink = Shortlink::create(array_merge($validated, [
            'short_code' => $shortCode,
        ]));

        return response()->json($shortlink, 201);
    }

    public function deactivate($id)
    {
        $shortlink = Shortlink::findOrFail($id);
        $shortlink->update(['is_active' => false]);

        return response()->json(['message' => 'Shortlink deactivated successfully']);
    }
}
