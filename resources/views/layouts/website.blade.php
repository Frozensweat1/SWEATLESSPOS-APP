@php
    $siteName = \App\Models\WebsiteSetting::get('site_name', 'SweatlessPOS');
    $siteLogo = \App\Models\WebsiteSetting::get('logo_image');
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $page->meta_description ?? config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $page->meta_title ?? ($page->title ?? config('app.name')) }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f8f9fa;
            padding-top: 70px;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
            z-index: 1000;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-logo {
            height: 40px;
            width: auto;
            object-fit: contain;
        }

        .nav-link {
            color: #555 !important;
            font-weight: 500;
            margin-left: 1rem;
        }

        .nav-link:hover {
            color: #007bff !important;
        }

        footer {
            background-color: #2d3436;
            color: white;
            margin-top: 4rem;
            padding: 2rem 0;
        }

        .hero {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 4rem 0;
            margin-bottom: 2rem;
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .hero-section,
        .about-section,
        .products-section,
        .gallery-section {
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed !important;
        }

        .gallery-album:hover {
            transform: scale(1.05);
        }

        .gallery-album:hover>.position-absolute {
            opacity: 1 !important;
        }

        .hover-lift:hover {
            transform: translateY(-10px);
        }

        @media (max-width: 768px) {
            .display-3 {
                font-size: 2rem !important;
            }

            .display-5 {
                font-size: 1.5rem !important;
            }
        }
    </style>

    @livewireStyles
</head>

<body class="antialiased">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="/">
                @if ($siteLogo)
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="{{ $siteName }}" class="navbar-logo">
                @else
                    <i class="fas fa-store"></i>
                @endif
                <span>{{ $siteName }}</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/products">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/testimonials">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/gallery">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/contact">Contact</a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="/dashboard">Dashboard</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <h6>About Us</h6>
                    <p>{{ \App\Models\WebsiteSetting::get('site_tagline', 'Your trusted POS solution') }}</p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Contact</h6>
                    <p>
                        📧 {{ \App\Models\WebsiteSetting::get('email', 'info@example.com') }}<br>
                        📱 {{ \App\Models\WebsiteSetting::get('phone', '+1 (555) 000-0000') }}<br>
                        📍 {{ \App\Models\WebsiteSetting::get('address', 'Your Address') }}
                    </p>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Follow Us</h6>
                    <div style="font-size: 1.5rem;">
                        @if (\App\Models\WebsiteSetting::get('facebook_url'))
                            <a href="{{ \App\Models\WebsiteSetting::get('facebook_url') }}" target="_blank"
                                class="text-white me-3" title="Facebook"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if (\App\Models\WebsiteSetting::get('instagram_url'))
                            <a href="{{ \App\Models\WebsiteSetting::get('instagram_url') }}" target="_blank"
                                class="text-white me-3" title="Instagram"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if (\App\Models\WebsiteSetting::get('twitter_url'))
                            <a href="{{ \App\Models\WebsiteSetting::get('twitter_url') }}" target="_blank"
                                class="text-white me-3" title="Twitter"><i class="fab fa-twitter"></i></a>
                        @endif
                        @if (\App\Models\WebsiteSetting::get('linkedin_url'))
                            <a href="{{ \App\Models\WebsiteSetting::get('linkedin_url') }}" target="_blank"
                                class="text-white me-3" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                        @endif
                        @if (\App\Models\WebsiteSetting::get('tiktok_url'))
                            <a href="{{ \App\Models\WebsiteSetting::get('tiktok_url') }}" target="_blank"
                                class="text-white me-3" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        @endif
                        @if (\App\Models\WebsiteSetting::get('whatsapp_number'))
                            <a href="https://wa.me/{{ \App\Models\WebsiteSetting::get('whatsapp_number') }}" target="_blank"
                                class="text-white me-3" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <h6>Newsletter</h6>
                    @livewire('website.newsletter-form')
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts
</body>

</html>
