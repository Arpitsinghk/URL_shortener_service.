<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class ApiController extends Controller
{
    public function index(Request $request)
{
    $urls = $request->user()->shortUrls()->latest()->get();

    return response()->json([
        'success' => true,
        'data' => $urls
    ]);
}

public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'original_url' => 'required|url',
        'expires_at' => 'nullable|date|after:now'
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $shortUrl = ShortUrl::create([
        'user_id' => $request->user()->id,
        'original_url' => $request->original_url,
        'short_code' => Str::random(6),
        'expires_at' => $request->expires_at
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Short URL created successfully.',
        'data' => $shortUrl
    ], 201);
}

public function show($id)
{
    $shortUrl = ShortUrl::find($id);

    if (!$shortUrl || $shortUrl->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'URL not found.'], 404);
    }

    return response()->json([
        'success' => true,
        'data' => $shortUrl
    ]);
}

public function update(Request $request, $id)
{
    $shortUrl = ShortUrl::find($id);

    if (!$shortUrl || $shortUrl->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'URL not found.'], 404);
    }

    $validator = Validator::make($request->all(), [
        'original_url' => 'required|url',
        'expires_at' => 'nullable|date|after:now'
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $shortUrl->update($request->only(['original_url', 'expires_at']));

    return response()->json([
        'success' => true,
        'message' => 'Short URL updated successfully.',
        'data' => $shortUrl
    ]);
}

public function destroy($id)
{
    $shortUrl = ShortUrl::find($id);

    if (!$shortUrl || $shortUrl->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'URL not found.'], 404);
    }

    $shortUrl->delete();

    return response()->json([
        'success' => true,
        'message' => 'Short URL deleted successfully.'
    ]);
}

public function disable($id)
{
    $shortUrl = ShortUrl::find($id);

    if (!$shortUrl || $shortUrl->user_id !== Auth::id()) {
        return response()->json(['success' => false, 'message' => 'URL not found.'], 404);
    }

    $shortUrl->is_active = false;
    $shortUrl->save();

    return response()->json([
        'success' => true,
        'message' => 'Short URL disabled successfully.'
    ]);
}


  public function stats($id, Request $request)
    {
        $shortUrl = ShortUrl::with('analytics')->find($id);

        if (!$shortUrl || $shortUrl->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'URL not found or unauthorized.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'url' => $shortUrl->original_url,
                'short_code' => $shortUrl->short_code,
                'total_redirects' => $shortUrl->analytics->count(),
                'last_accessed_at' => $shortUrl->analytics->max('created_at'),
                'analytics' => $shortUrl->analytics
            ]
        ]);
    }

}
