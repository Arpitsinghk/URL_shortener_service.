<!DOCTYPE html>
<html>
<head>
    <title>Redirecting...</title>
    <meta http-equiv="refresh" content="{{ $redirectTime }};url={{ $shortUrl->original_url }}">
</head>
<body>
    <h2>You're being redirected...</h2>
    <p>To: <a href="{{ $shortUrl->original_url }}">{{ $shortUrl->original_url }}</a></p>
    <p>You'll be redirected in {{ $redirectTime }} seconds.</p>
</body>
</html>
