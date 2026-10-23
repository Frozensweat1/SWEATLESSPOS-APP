@props(['title' => null, 'wide' => false])
@php
    $siteName = $settings->site_name ?? config('app.name', 'Sweatless POS');
    $logoUrl = $settings->LogoUrl ?? asset('assets/img/logo.png');
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <meta name="theme-color" content="#4f46e5">
    <title>{{ $title ? $title . ' | ' : '' }}{{ $siteName }}</title>
    <link rel="icon" href="{{ $settings->FaviconUrl ?? $logoUrl }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}?v={{ @filemtime(public_path('assets/css/site.css')) }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}?v={{ @filemtime(public_path('assets/css/auth.css')) }}">
</head>
<body class="auth-body">
<div class="auth-shell">
    <aside class="auth-aside" aria-hidden="true">
        <a href="{{ url('/') }}" class="brand" tabindex="-1">
            <img src="{{ $logoUrl }}" alt="" width="40" height="40" style="background:#fff;padding:3px">
            <span>{{ $siteName }}</span>
        </a>
        <div>
            <h2>Sell faster.<br>Stay in control.</h2>
            <p>One secure place to run your point of sale, stock, customers and reports.</p>
            <ul class="auth-points">
                <li><span class="ico"><x-site.icon name="shopping-bag" :size="18" /></span> Fast checkout with live stock checks</li>
                <li><span class="ico"><x-site.icon name="package" :size="18" /></span> Inventory and multi-store management</li>
                <li><span class="ico"><x-site.icon name="check" :size="18" /></span> Role-based access for your whole team</li>
            </ul>
        </div>
        <small>&copy; {{ date('Y') }} {{ $siteName }}</small>
    </aside>

    <div class="auth-main">
        <div class="auth-top">
            <a href="{{ url('/') }}" class="brand">
                <img src="{{ $logoUrl }}" alt="" width="36" height="36">
                <span>{{ $siteName }}</span>
            </a>
            <a href="{{ url('/') }}" class="back"><x-site.icon name="arrow-left" :size="16" /> Back to website</a>
        </div>

        <main class="auth-center">
            <div class="auth-card {{ $wide ? 'auth-card--wide' : '' }}">
                {{ $slot }}
            </div>
        </main>

        <p class="auth-legal">Protected area. Unauthorised access is prohibited.</p>
    </div>
</div>

<script>
    document.addEventListener('click', function (e) {
        var t = e.target.closest('[data-toggle-password]');
        if (!t) return;
        var input = document.getElementById(t.getAttribute('data-toggle-password'));
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        t.textContent = show ? 'Hide' : 'Show';
        t.setAttribute('aria-pressed', show ? 'true' : 'false');
    });
    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.dataset.submitted === '1') { e.preventDefault(); return; }
        form.dataset.submitted = '1';
        var btn = form.querySelector('button[type="submit"]');
        if (btn) { btn.classList.add('is-busy'); btn.setAttribute('aria-busy', 'true'); }
    });
    // Restore the form if the user navigates back (bfcache) after submitting.
    window.addEventListener('pageshow', function (e) {
        if (!e.persisted) return;
        document.querySelectorAll('form[data-submitted]').forEach(function (f) { delete f.dataset.submitted; });
        document.querySelectorAll('.is-busy').forEach(function (b) { b.classList.remove('is-busy'); b.removeAttribute('aria-busy'); });
    });
</script>
@stack('scripts')
</body>
</html>
