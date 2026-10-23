@props(['name', 'size' => 20])
@php
    $paths = [
        'menu' => '<path d="M4 6h16M4 12h16M4 18h16"/>',
        'x' => '<path d="M18 6 6 18M6 6l12 12"/>',
        'phone' => '<path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.2a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z"/>',
        'mail' => '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>',
        'map-pin' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'arrow-left' => '<path d="M19 12H5m7-7-7 7 7 7"/>',
        'arrow-right' => '<path d="M5 12h14m-7-7 7 7-7 7"/>',
        'chevron-left' => '<path d="m15 18-6-6 6-6"/>',
        'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
        'star' => '<path d="m12 2 3.1 6.3 6.9 1-5 4.9 1.2 6.8L12 17.8 5.8 21l1.2-6.8-5-4.9 6.9-1z"/>',
        'user' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'package' => '<path d="m7.5 4.3 9 5.2M21 8v8a2 2 0 0 1-1 1.7l-7 4a2 2 0 0 1-2 0l-7-4A2 2 0 0 1 3 16V8a2 2 0 0 1 1-1.7l7-4a2 2 0 0 1 2 0l7 4A2 2 0 0 1 21 8z"/><path d="m3.3 7 8.7 5 8.7-5M12 22V12"/>',
        'image' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/>',
        'shopping-bag' => '<path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><path d="M3 6h18M16 10a4 4 0 0 1-8 0"/>',
        'search' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'check' => '<path d="M20 6 9 17l-5-5"/>',
        'facebook' => '<path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/>',
        'instagram' => '<rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.4a4 4 0 1 1-7.9 1.2 4 4 0 0 1 7.9-1.2zM17.5 6.5h.01"/>',
        'twitter' => '<path d="M23 3a10.9 10.9 0 0 1-3.1 1.5 4.5 4.5 0 0 0-7.7 4.1A12.9 12.9 0 0 1 2.8 3.9a4.5 4.5 0 0 0 1.4 6A4.5 4.5 0 0 1 2.1 9.3a4.5 4.5 0 0 0 3.6 4.5 4.5 4.5 0 0 1-2 .1 4.5 4.5 0 0 0 4.2 3.1A9 9 0 0 1 1 18.8 12.8 12.8 0 0 0 8 21c8.3 0 13-7 13-13v-.6A9.2 9.2 0 0 0 23 3z"/>',
        'linkedin' => '<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/>',
        'message-circle' => '<path d="M21 11.5a8.4 8.4 0 0 1-9 8.4 8.5 8.5 0 0 1-3.8-.9L3 21l1.9-5.2A8.4 8.4 0 0 1 3.9 11.5a8.5 8.5 0 0 1 8.4-8.5h.4a8.4 8.4 0 0 1 8.3 8z"/>',
        'music' => '<path d="M9 18V5l12-2v13"/><circle cx="6" cy="18" r="3"/><circle cx="18" cy="16" r="3"/>',
        'wifi-off' => '<path d="m2 2 20 20M8.5 16.4a5 5 0 0 1 7 0M2 8.8a15 15 0 0 1 4.2-2.6M10.7 5.1A15 15 0 0 1 22 8.8M5 12.9a10 10 0 0 1 5.2-2.7M13.9 10.3a10 10 0 0 1 5.1 2.6M12 20h.01"/>',
        'download' => '<path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M7 10l5 5 5-5M12 15V3"/>',
        'refresh' => '<path d="M23 4v6h-6M1 20v-6h6"/><path d="M3.5 9a9 9 0 0 1 14.9-3.4L23 10M1 14l4.6 4.4A9 9 0 0 0 20.5 15"/>',
        'send' => '<path d="m22 2-7 20-4-9-9-4zM22 2 11 13"/>',
        'clock' => '<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>',
    ];
@endphp
<svg {{ $attributes->merge(['class' => 'icon']) }} width="{{ $size }}" height="{{ $size }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false">{!! $paths[$name] ?? '' !!}</svg>
