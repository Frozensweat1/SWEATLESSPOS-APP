<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">About {{ config('app.name') }}</h1>
                    <p class="lead text-white">Learn more about our company and mission</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <div class="card">
            <div class="card-body">
                @if ($page->featured_image)
                    <img src="{{ asset('storage/' . $page->featured_image) }}" class="img-fluid mb-4"
                        alt="{{ $page->title }}" style="max-height: 400px; object-fit: cover;">
                @endif
                <div class="content">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>

    <!-- Founder Section -->
    @if ($founderName)
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row align-items-center">
                    @if ($founderImage)
                        <div class="col-md-4 mb-4 mb-md-0 text-center">
                            <img src="{{ asset('storage/' . $founderImage) }}" class="img-fluid rounded-circle shadow"
                                alt="{{ $founderName }}"
                                style="max-width: 300px; width: 100%; aspect-ratio: 1 / 1; object-fit: cover; border: 5px solid white;">
                        </div>
                        <div class="col-md-8">
                            <h3 class="fw-bold mb-1">{{ $founderName }}</h3>
                            @if ($founderTitle)
                                <p class="text-muted fw-bold mb-3">{{ $founderTitle }}</p>
                            @endif
                            @if ($founderBio)
                                <p class="fs-5 lh-lg">{!! nl2br(e($founderBio)) !!}</p>
                            @endif
                        </div>
                    @else
                        <div class="col-12">
                            <h3 class="fw-bold mb-1">{{ $founderName }}</h3>
                            @if ($founderTitle)
                                <p class="text-muted fw-bold mb-3">{{ $founderTitle }}</p>
                            @endif
                            @if ($founderBio)
                                <p class="fs-5 lh-lg">{!! nl2br(e($founderBio)) !!}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
</div>
