<div>
    @if (count($testimonials) > 0)
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5">
                        @php $current = $testimonials[$currentIndex]; @endphp

                        <div class="row align-items-center">
                            @if ($current->author_image)
                                <div class="col-md-3 text-center mb-3 mb-md-0">
                                    <img src="{{ asset('storage/' . $current->author_image) }}"
                                        class="img-fluid rounded-circle shadow-sm"
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                                <div class="col-md-9">
                            @else
                                <div class="col">
                            @endif
                                <div class="mb-3">
                                    @for ($i = 0; $i < $current->rating; $i++)
                                        <i class="fas fa-star text-warning"></i>
                                    @endfor
                                    @for ($i = $current->rating; $i < 5; $i++)
                                        <i class="fas fa-star text-secondary"></i>
                                    @endfor
                                    <small class="text-muted ms-2">({{ $current->rating }}/5 stars)</small>
                                </div>

                                <p class="lead mb-3">"{{ $current->testimonial_text }}"</p>

                                <h5 class="fw-bold mb-1">{{ $current->author_name }}</h5>
                                @if ($current->author_title)
                                    <p class="text-muted">{{ $current->author_title }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (count($testimonials) > 1)
            <div class="d-flex justify-content-center gap-2 mt-4">
                <button wire:click="prev" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-chevron-left"></i> Previous
                </button>
                <div class="align-self-center text-muted small">
                    {{ $currentIndex + 1 }} / {{ count($testimonials) }}
                </div>
                <button wire:click="next" class="btn btn-outline-secondary btn-sm">
                    Next <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        @endif
    @else
        <div class="alert alert-info text-center">No testimonials available at the moment.</div>
    @endif
</div>