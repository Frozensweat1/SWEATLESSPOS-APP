<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#4f46e5">
    <title>You're offline</title>
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
</head>
<body>
    <main class="container center" style="display:grid;place-content:center;gap:1rem;min-height:100vh;max-width:480px">
        <x-site.icon name="wifi-off" :size="56" style="margin-inline:auto;color:var(--muted)" />
        <h1 style="font-size:1.8rem">You're offline</h1>
        <p class="muted">This page isn't saved on your device yet. Check your connection and try again — pages you've already visited are still available.</p>
        <div>
            <button type="button" class="btn" onclick="location.reload()"><x-site.icon name="refresh" :size="18" /> Try again</button>
            <a class="btn btn--ghost" href="{{ url('/') }}">Home</a>
        </div>
    </main>
</body>
</html>
