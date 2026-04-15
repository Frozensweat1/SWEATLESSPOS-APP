<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">{{ $album->name }}</h1>
                    @if($album->description)
                        <p class="lead text-white">{{ $album->description }}</p>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <!-- Back to Gallery -->
        <div class="mb-4">
            <a href="{{ route('website.gallery') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back to Gallery
            </a>
        </div>

        <!-- Album Images -->
        <div class="row">
            @forelse($images as $image)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 cursor-pointer" data-bs-toggle="modal" data-bs-target="#imageModal"
                        onclick="displayImage('{{ asset('storage/' . $image->image_path) }}', '{{ $image->title }}', '{{ $image->description }}')">
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
                    <div class="alert alert-info">
                        No images in this album yet.
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageTitle">Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Image" style="max-height: 70vh;">
                    <p id="imageDescription" class="mt-3 text-muted"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function displayImage(src, title, description) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageTitle').textContent = title || 'Image';
            document.getElementById('imageDescription').textContent = description || '';
        }
    </script>
</div>