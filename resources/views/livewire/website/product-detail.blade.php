<div>
    <!-- Breadcrumb -->
    <div class="bg-light py-3 mb-4">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="/products" class="text-decoration-none">Products</a></li>
                    @if ($product->category)
                        <li class="breadcrumb-item"><a href="/products?category={{ $product->category->id }}"
                                class="text-decoration-none">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="container my-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-lg-5 mb-4">
                <div class="sticky-top" style="top: 20px;">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded"
                            alt="{{ $product->name }}" style="width: 100%; object-fit: cover; max-height: 500px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center"
                            style="height: 500px;">
                            <i class="fas fa-box text-secondary fa-5x"></i>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Details -->
            <div class="col-lg-7">
                <!-- Product Info -->
                <div class="mb-4">
                    @if ($product->category)
                        <span class="badge bg-primary mb-2">{{ $product->category->name }}</span>
                    @endif
                    <h1 class="display-5 fw-bold mb-2">{{ $product->name }}</h1>

                    <!-- Rating and Stock Status -->
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-success">In Stock</span>
                            <span class="ms-2 text-muted">{{ $product->stock_quantity }} available</span>
                        </div>
                    </div>

                    <!-- Description -->
                    @if ($product->description)
                        <p class="fs-5 text-muted mb-4">{{ $product->description }}</p>
                    @endif
                </div>

                <!-- Price Section -->
                <div class="card border-0 bg-light p-4 mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted mb-1">Price</p>
                                <h2 class="text-primary fw-bold">GHS {{ number_format($product->price, 2) }}</h2>
                            </div>
                            @if ($product->cost_price && $product->cost_price < $product->price)
                                <p class="text-success small">
                                    <i class="fas fa-check-circle"></i> Best Price
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <p class="text-muted mb-1">SKU</p>
                                <p class="fw-bold">{{ $product->sku ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Details Grid -->
                <div class="card border-0 p-4 mb-4">
                    <h5 class="fw-bold mb-3">Product Information</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Barcode</p>
                            <p class="fw-bold">{{ $product->barcode ?? 'Not specified' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="text-muted small mb-1">Category</p>
                            <p class="fw-bold">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        </div>
                        @if ($product->manufacture_date)
                            <div class="col-md-6 mb-3">
                                <p class="text-muted small mb-1">Manufacture Date</p>
                                <p class="fw-bold">
                                    {{ \Carbon\Carbon::parse($product->manufacture_date)->format('d M Y') }}</p>
                            </div>
                        @endif
                        @if ($product->expiry_date)
                            <div class="col-md-6 mb-3">
                                <p class="text-muted small mb-1">Expiry Date</p>
                                <p
                                    class="fw-bold {{ \Carbon\Carbon::parse($product->expiry_date)->isPast() ? 'text-danger' : '' }}">
                                    {{ \Carbon\Carbon::parse($product->expiry_date)->format('d M Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Call Us Button -->
                <div class="mb-4">
                    <button class="btn btn-success btn-lg w-100" wire:click="callUs">
                        <i class="fas fa-phone"></i> Call Us Now
                    </button>
                </div>

                <!-- Info Message -->
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Interested in this product? Click the button above to call us for
                    more information and to place your order.
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="row mt-5 pt-5 border-top">
                <div class="col-12 mb-4">
                    <h3 class="fw-bold">Related Products</h3>
                </div>
                @foreach ($relatedProducts as $relatedProduct)
                    <div class="col-md-6 col-lg-3 mb-4">
                        <div class="card h-100 shadow-sm">
                            @if ($relatedProduct->image)
                                <img src="{{ asset('storage/' . $relatedProduct->image) }}" class="card-img-top"
                                    alt="{{ $relatedProduct->name }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                    style="height: 200px;">
                                    <i class="fas fa-box text-secondary fa-2x"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h6 class="card-title">{{ $relatedProduct->name }}</h6>
                                <p class="card-text text-muted small">
                                    {{ Str::limit($relatedProduct->description, 40) }}</p>
                                <p class="h6 text-primary mb-2">GHS {{ number_format($relatedProduct->price, 2) }}</p>
                            </div>
                            <div class="card-footer bg-white border-top">
                                <a href="/product/{{ $relatedProduct->id }}"
                                    class="btn btn-sm btn-outline-primary w-100">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
