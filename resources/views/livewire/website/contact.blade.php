<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">Contact Us</h1>
                    <p class="lead text-white">We'd love to hear from you. Get in touch with us.</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Page Title -->
                <div class="mb-5">
                    <h1 class="display-4 fw-bold mb-3">{{ $page->title ?? 'Contact Us' }}</h1>
                    <div class="lead text-muted">
                        {!! $page->content ?? '<p>Have questions? We would love to hear from you.</p>' !!}
                    </div>
                </div>

                <!-- Contact Information Cards -->
                <div class="row mb-5">
                    <!-- Phone -->
                    @if ($phone)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-phone text-primary" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5 class="card-title">Phone</h5>
                                    <p class="card-text text-muted">
                                        <a href="tel:{{ $phone }}" class="text-decoration-none">
                                            {{ $phone }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Email -->
                    @if ($email)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-envelope text-success" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5 class="card-title">Email</h5>
                                    <p class="card-text text-muted">
                                        <a href="mailto:{{ $email }}" class="text-decoration-none">
                                            {{ $email }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Address -->
                    @if ($address)
                        <div class="col-md-12 mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-map-marker-alt text-danger" style="font-size: 2rem;"></i>
                                    </div>
                                    <h5 class="card-title">Address</h5>
                                    <p class="card-text text-muted">{{ $address }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Google Map -->
                @if ($mapsUrl)
                    <div class="mb-5">
                        <h3 class="mb-4 fw-bold">Find Us on the Map</h3>
                        <div class="rounded overflow-hidden shadow-sm">
                            <iframe src="{{ $mapsUrl }}" width="100%" height="450" style="border:0;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                @endif

                <!-- Business Hours (Optional) -->
                @php
                    $businessHours = \App\Models\WebsiteSetting::get('business_hours', null);
                @endphp
                @if ($businessHours && is_array($businessHours))
                    <div class="card border-0 shadow-sm mb-5">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-4">Business Hours</h5>
                            <div class="row">
                                @foreach ($businessHours as $day => $hours)
                                    <div class="col-md-6 mb-3">
                                        <strong>{{ ucfirst($day) }}:</strong>
                                        <span class="text-muted">{{ $hours ?? 'Closed' }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Contact Form CTA -->
                <div class="mb-5">
                    @livewire('Website.ContactFormSubmission')
                </div>
            </div>
        </div>
    </div>

    <!-- Add Font Awesome if not already included -->
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush
