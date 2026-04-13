<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">{{ $page->title ?? 'Customer Testimonials' }}</h1>
                    <p class="lead text-white">Hear from our satisfied customers</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <!-- Page Introduction -->
            @if ($page->content)
                <div class="row mb-5">
                    <div class="col-lg-8 mx-auto text-center">
                        <div class="lead text-muted">
                            {!! $page->content !!}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Testimonials Grid -->
            @if ($testimonials->count() > 0)
                <div class="row g-4">
                    @foreach ($testimonials as $testimonial)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift"
                                style="transition: transform 0.3s ease;">
                                <div class="card-body d-flex flex-column">
                                    <!-- Rating -->
                                    <div class="mb-3">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i
                                                class="fas fa-star {{ $i < $testimonial->rating ? 'text-warning' : 'text-light' }}"></i>
                                        @endfor
                                    </div>

                                    <!-- Testimonial Text -->
                                    <p class="card-text flex-grow-1 text-muted fst-italic">
                                        "{{ $testimonial->testimonial_text }}"
                                    </p>

                                    <!-- Author Info -->
                                    <div class="d-flex align-items-center mt-4 pt-3 border-top">
                                        @if ($testimonial->author_image)
                                            <img src="{{ asset('storage/' . $testimonial->author_image) }}"
                                                alt="{{ $testimonial->author_name }}" class="rounded-circle me-3"
                                                style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-3"
                                                style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $testimonial->author_name }}</h6>
                                            @if ($testimonial->author_title)
                                                <small class="text-muted">{{ $testimonial->author_title }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            No testimonials available yet. Check back soon!
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center text-white">
                    <h2 class="display-5 fw-bold mb-3">Share Your Experience</h2>
                    <p class="lead mb-4">We'd love to hear about your experience with us!</p>
                    <a href="/contact" class="btn btn-light btn-lg">
                        <i class="fas fa-envelope me-2"></i> Get in Touch
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Styles -->
    <style>
        .hover-lift:hover {
            transform: translateY(-10px);
        }
    </style>
</div>
