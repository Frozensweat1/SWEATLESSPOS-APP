<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">Gallery</h1>
                    <p class="lead text-white">Browse our photo collection</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        @if ($selectedAlbum)
            <!-- Selected Album View -->
            <div class="mb-4">
                <button wire:click="clearSelection" class="btn btn-secondary mb-3">
                    <i class="bi bi-arrow-left"></i> Back to Albums
                </button>
            </div>

            <h2 class="mb-4">{{ $selectedAlbum->name }}</h2>
            @if ($selectedAlbum->description)
                <p class="text-muted mb-4">{{ $selectedAlbum->description }}</p>
            @endif

            <div class="row">
                @forelse($selectedImages as $image)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <div class="card h-100 cursor-pointer" data-bs-toggle="modal" data-bs-target="#imageModal"
                            onclick="displayImage('{{ asset('storage/' . $image->image_path) }}', '{{ $image->title }}')">
                            <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                alt="{{ $image->title }}" style="height: 250px; object-fit: cover;">
                            @if ($image->title)
                                <div class="card-body">
                                    <h6 class="card-title">{{ $image->title }}</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">No images in this album yet.</p>
                    </div>
                @endforelse
            </div>
        @else
            <!-- Albums View -->
            <div class="row">
                @forelse($albums as $album)
                    <div class="col-md-4 col-lg-3 mb-4">
                        <a href="{{ route('website.gallery.detail', $album->slug) }}" class="text-decoration-none">
                            <div class="card h-100">
                                @if ($album->cover_image)
                                    <img src="{{ asset('storage/' . $album->cover_image) }}" class="card-img-top"
                                        alt="{{ $album->name }}" style="height: 250px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                        style="height: 250px;">
                                        <span class="text-muted">No Cover Image</span>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title text-dark">{{ $album->name }}</h5>
                                    @if ($album->description)
                                        <p class="card-text text-muted small">{{ Str::limit($album->description, 80) }}</p>
                                    @endif
                                    <span class="badge bg-primary">
                                        {{ $album->images()->count() }} images
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">
                            No albums available yet.
                        </div>
                    </div>
                @endforelse
            </div>
        @endif
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageTitle">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img id="modalImage" src="" class="img-fluid" alt="Image">
                </div>
            </div>
        </div>
    </div>

    <script>
        function displayImage(src, title) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageTitle').textContent = title || 'Image';
        }
    </script>
</div>
