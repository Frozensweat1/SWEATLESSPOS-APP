@php
    use App\Models\WebsiteSetting as S;

    $siteName = S::get('site_name', config('app.name', 'Sweatless POS'));
    $siteTagline = S::get('site_tagline', '');
    $siteDesc = S::get('site_description', $siteTagline);
    $logo = S::get('logo_image');
    $logoUrl = $logo ? asset('storage/' . $logo) : asset('assets/img/icon-192.png');
    $phone = S::get('phone');
    $email = S::get('email');
    $address = S::get('address');
    $whatsapp = preg_replace('/\D+/', '', (string) S::get('whatsapp_number', ''));
    $pageTitle = isset($title) && $title ? $title . ' | ' . $siteName : $siteName . ($siteTagline ? ' — ' . $siteTagline : '');
    $pageDesc = \Illuminate\Support\Str::limit(strip_tags((string) ($description ?? $siteDesc)), 160);
    $pageImage = $image ?? $logoUrl;
    $links = [
        ['/', 'Home', request()->is('/')],
        ['about', 'About', request()->is('about')],
        ['products', 'Products', request()->is('products*', 'product/*')],
        ['gallery', 'Gallery', request()->is('gallery*')],
        ['testimonials', 'Reviews', request()->is('testimonials')],
        ['contact', 'Contact', request()->is('contact')],
    ];
    $social = [
        'facebook' => S::get('facebook_url'),
        'instagram' => S::get('instagram_url'),
        'twitter' => S::get('twitter_url'),
        'linkedin' => S::get('linkedin_url'),
        'music' => S::get('tiktok_url'),
    ];
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $pageDesc }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $pageDesc }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $pageImage }}">
    <meta name="twitter:card" content="summary_large_image">

    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ $siteName }}">
    <link rel="manifest" href="{{ url('/manifest.webmanifest') }}">
    <link rel="icon" href="{{ asset('assets/img/icon-192.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('assets/img/icon-192.png') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}?v={{ @filemtime(public_path('assets/css/site.css')) }}">
    <script data-navigate-once>window.SITE_PWA = {!! json_encode(['sw' => asset('sw.js'), 'scope' => rtrim(url('/'), '/') . '/'], JSON_UNESCAPED_SLASHES) !!};</script>
    <script src="{{ asset('assets/js/site.js') }}?v={{ @filemtime(public_path('assets/js/site.js')) }}" defer data-navigate-once></script>

    @isset($jsonLd)
        <script type="application/ld+json">{!! json_encode($jsonLd, JSON_HEX_TAG | JSON_HEX_AMP | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset
    @livewireStyles
</head>
<body>
    <a href="#main" class="sr-only">Skip to content</a>

    <header class="site-header" x-data="{ open: false }" @keydown.escape.window="open = false">
        <div class="container">
            <a href="{{ url('/') }}" class="brand" wire:navigate>
                <img src="{{ $logoUrl }}" alt="" width="36" height="36">
                <span>{{ $siteName }}</span>
            </a>

            <nav class="nav" aria-label="Main">
                @foreach ($links as [$path, $label, $active])
                    <a href="{{ url($path) }}" wire:navigate @if ($active) aria-current="page" @endif>{{ $label }}</a>
                @endforeach
                <a href="{{ url('/dashboard') }}">Staff Portal</a>
            </nav>

            <button type="button" class="menu-btn" @click="open = true" aria-label="Open menu" :aria-expanded="open">
                <x-site.icon name="menu" :size="26" />
            </button>
        </div>

        <div x-show="open" x-cloak class="drawer-backdrop" @click="open = false" x-transition.opacity></div>
        <aside x-show="open" x-cloak class="drawer" aria-label="Mobile menu" x-transition>
            <div class="drawer-top">
                <strong>{{ $siteName }}</strong>
                <button type="button" class="menu-btn" @click="open = false" aria-label="Close menu"><x-site.icon name="x" :size="24" /></button>
            </div>
            @foreach ($links as [$path, $label, $active])
                <a href="{{ url($path) }}" wire:navigate @click="open = false" @if ($active) aria-current="page" @endif>{{ $label }}</a>
            @endforeach
            <a href="{{ url('/dashboard') }}">Staff Portal</a>
            <button type="button" class="btn btn--ghost install-btn" hidden style="margin-top:auto"><x-site.icon name="download" :size="18" /> Install app</button>
        </aside>
    </header>

    <main id="main">
        {{ $slot }}
    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <h4>{{ $siteName }}</h4>
                    @if ($siteDesc)<p>{{ \Illuminate\Support\Str::limit($siteDesc, 160) }}</p>@endif
                    <div class="social">
                        @foreach ($social as $icon => $href)
                            @if ($href)
                                <a href="{{ $href }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $icon === 'music' ? 'TikTok' : ucfirst($icon) }}"><x-site.icon :name="$icon" :size="18" /></a>
                            @endif
                        @endforeach
                        @if ($whatsapp)
                            <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><x-site.icon name="message-circle" :size="18" /></a>
                        @endif
                    </div>
                </div>
                <div>
                    <h4>Explore</h4>
                    <ul>
                        @foreach ($links as [$path, $label])
                            <li><a href="{{ url($path) }}" wire:navigate>{{ $label }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4>Contact</h4>
                    <ul>
                        @if ($address)<li><x-site.icon name="map-pin" :size="16" /> {{ $address }}</li>@endif
                        @if ($phone)<li><x-site.icon name="phone" :size="16" /> <a href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}">{{ $phone }}</a></li>@endif
                        @if ($email)<li><x-site.icon name="mail" :size="16" /> <a href="mailto:{{ $email }}">{{ $email }}</a></li>@endif
                    </ul>
                </div>
                <div>
                    <h4>Stay updated</h4>
                    @livewire('website.newsletter-form')
                    <button type="button" class="btn btn--outline-light install-btn" hidden style="margin-top:.75rem"><x-site.icon name="download" :size="18" /> Install app</button>
                </div>
            </div>
            <div class="copyright">
                <span>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</span>
            </div>
        </div>
    </footer>

    @livewireScripts
</body>
</html>
