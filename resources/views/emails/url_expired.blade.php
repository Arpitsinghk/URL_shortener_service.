<!DOCTYPE html>
<html>
<head>
    <title>URL Expired</title>
</head>
<body>
    <h2>Your short URL has expired</h2>
    <p><strong>Original URL:</strong> {{ $url->original_url }}</p>
    <p><strong>Short URL:</strong> {{ url($url->short_code) }}</p>
    <p>This link expired on {{ $url->expires_at->format('Y-m-d H:i') }}</p>
    <p><a href="{{ url('/dashboard') }}">Manage your URLs</a></p>
</body>
</html>
