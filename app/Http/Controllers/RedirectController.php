<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UrlAnalytics;
use App\Models\ShortUrl;


class RedirectController extends Controller
{
    public function redirect($code, Request $request)
{
    $shortUrl = ShortUrl::where('short_code', $code)->firstOrFail();

    if (!$shortUrl->is_active) {
        abort(403, 'This short URL is disabled.');
    }

    // Save analytics
    UrlAnalytics::create([
        'short_url_id' => $shortUrl->id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return redirect($shortUrl->original_url);
}

public function exitPage($short_code, Request $request)
{
    $shortUrl = ShortUrl::where('short_code', $short_code)->firstOrFail();

    if (!$shortUrl->is_active) {
        abort(403, 'This short URL has been disabled.');
    }

    // Record analytics
    UrlAnalytics::create([
        'short_url_id' => $shortUrl->id,
        'ip_address' => $request->ip(),
        'user_agent' => $request->userAgent(),
    ]);

    return view('redirect.exit', [
        'shortUrl' => $shortUrl,
        'redirectTime' => 5 // seconds
    ]);
}
}
