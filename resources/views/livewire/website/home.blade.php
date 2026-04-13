<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 500px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-3 fw-bold text-white mb-4">{{ $page->title ?? 'Welcome to Our Store' }}</h1>
                    <p class="lead text-white mb-4">{{ $settings['site_tagline'] ?? 'Your trusted business partner' }}
                    </p>
                    <div class="d-flex gap-2">
                        <a href="/products" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag"></i> Shop Now
                        </a>
                        <a href="/about" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-info-circle"></i> Learn More
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Content -->
    @if ($page->content)
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-5">
                                @if ($settings['home_description_image'])
                                    <img src="{{ asset('storage/' . $settings['home_description_image']) }}"
                                        class="img-fluid mb-4 rounded" alt="{{ $page->title }}"
                                        style="width: 100%; height: auto; object-fit: cover;">
                                @elseif ($page->featured_image)
                                    <img src="{{ asset('storage/' . $page->featured_image) }}"
                                        class="img-fluid mb-4 rounded" alt="{{ $page->title }}">
                                @endif
                                <div class="content fs-5">
                                    {!! $page->content !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- About Section -->
    @if ($aboutPage)
        <section class="about-section position-relative py-5"
            style="background-image: {{ $aboutBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: rgba(255, 255, 255, 0.95); z-index: 1;"></div>
            <div class="container position-relative" style="z-index: 2;">
                <div class="row align-items-center">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="display-5 fw-bold mb-4 text-center">{{ $aboutPage->title }}</h2>
                        <div class="about-content fs-5 text-muted lh-lg">
                            @if ($aboutPage->featured_image)
                                <img src="{{ asset('storage/' . $aboutPage->featured_image) }}"
                                    class="img-fluid mb-4 rounded" alt="{{ $aboutPage->title }}"
                                    style="max-width: 400px; margin: 0 auto; display: block;">
                            @endif
                            {!! $aboutPage->content !!}
                        </div>
                        <div class="text-center mt-4">
                            <a href="/about" class="btn btn-primary btn-lg">
                                Read Full Story
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Featured Products Section -->
    @if ($featuredProducts->count() > 0)
        <section class="products-section position-relative py-5"
            style="background-image: {{ $productsBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 600px; display: flex; align-items: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: rgba(0, 0, 0, 0.7); z-index: 1;"></div>
            <div class="container position-relative" style="z-index: 2;">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold text-white mb-2">Featured Products</h2>
                    <p class="lead text-white-50">Discover our best-selling items</p>
                </div>
                <div class="row g-4">
                    @foreach ($featuredProducts as $product)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 bg-dark border-0 shadow-lg hover-lift"
                                style="transition: transform 0.3s ease;">
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                        alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center"
                                        style="height: 200px;">
                                        <i class="fas fa-box text-light fa-3x"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title text-white">{{ $product->name }}</h5>
                                    <p class="card-text text-white-50">
                                        {{ Str::limit($product->description ?? 'Premium quality product', 50) }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent border-top border-secondary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 text-primary mb-0">
                                            {{ $product->price ? 'GHS ' . number_format($product->price, 2) : 'N/A' }}
                                        </span>
                                        <a href="/product/{{ $product->id }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="/products" class="btn btn-light btn-lg">
                        <i class="fas fa-shop"></i> View All Products
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Testimonials Section -->
    <section class="testimonials-section py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-2">What Our Customers Say</h2>
                <p class="lead text-muted">Trusted by thousands of happy customers</p>
            </div>
            @livewire('Website.TestimonialsCarousel')
            <div class="text-center mt-5">
                <a href="/testimonials" class="btn btn-primary btn-lg">
                    <i class="fas fa-star"></i> Read All Testimonials
                </a>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    @if ($galleryAlbums->count() > 0)
        <section class="gallery-section position-relative py-5"
            style="background-image: {{ $galleryBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 600px; display: flex; align-items: center;">
            <div class="position-absolute top-0 start-0 w-100 h-100"
                style="background: rgba(255, 255, 255, 0.92); z-index: 1;"></div>
            <div class="container position-relative" style="z-index: 2;">
                <div class="text-center mb-5">
                    <h2 class="display-5 fw-bold mb-2">Gallery</h2>
                    <p class="lead text-muted">Explore our collection of moments</p>
                </div>
                <div class="row g-4">
                    @foreach ($galleryAlbums as $album)
                        <div class="col-md-6 col-lg-3">
                            <div class="gallery-album rounded overflow-hidden shadow-lg position-relative group"
                                style="height: 250px; cursor: pointer; transition: transform 0.3s ease;">
                                @php
                                    $firstImage = $album->images()->first();
                                @endphp
                                @if ($firstImage)
                                    <img src="{{ asset('storage/' . $firstImage->image_path) }}" class="w-100 h-100"
                                        style="object-fit: cover;" alt="{{ $album->title }}">
                                @else
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                        <i class="fas fa-images text-secondary fa-3x"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50 d-flex align-items-center justify-content-center"
                                    style="opacity: 0; transition: opacity 0.3s ease;">
                                    <div class="text-center text-white">
                                        <h5 class="mb-2">{{ $album->title }}</h5>
                                        <small class="badge bg-primary">
                                            {{ $album->images()->count() }} images
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="text-center mt-5">
                    <a href="/gallery" class="btn btn-primary btn-lg">
                        <i class="fas fa-images"></i> View Full Gallery
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- Image Hover Effect Style -->
    <style>
        .gallery-album:hover {
            transform: scale(1.05);
        }

        .gallery-album:hover>.position-absolute {
            opacity: 1 !important;
        }

        .hover-lift:hover {
            transform: translateY(-10px);
        }

        .hero-section {
            position: relative;
        }

        .z-1 {
            position: relative;
            z-index: 1;
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
</div>
