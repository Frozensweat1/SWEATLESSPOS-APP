<div>
    <!-- Hero Section -->
    <section class="hero-section position-relative"
        style="background-image: {{ $heroBackgroundUrl }}; background-size: cover; background-position: center; background-attachment: fixed; min-height: 400px; display: flex; align-items: center;">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: rgba(0, 0, 0, 0.5); z-index: 1;">
        </div>
        <div class="container position-relative" style="z-index: 2;">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold text-white mb-3">Our Products</h1>
                    <p class="lead text-white">Browse our collection of quality products</p>
                </div>
            </div>
        </div>
    </section>

    <div class="container my-5">
        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-3">
                <input type="text" wire:model.live="search" class="form-control" placeholder="Search products...">
            </div>
            <div class="col-md-3">
                <select wire:model.live="category_id" class="form-select">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select wire:model.live="sortBy" class="form-select">
                    <option value="name">Name (A-Z)</option>
                    <option value="price">Price (Low to High)</option>
                    <option value="-price">Price (High to Low)</option>
                </select>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="row">
            @forelse($products as $product)
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                style="height: 200px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($product->description, 50) }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0">GHS {{ number_format($product->price, 2) }}</span>
                                <span class="badge bg-success">{{ $product->stock_quantity }} in stock</span>
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top">
                            <a href="/product/{{ $product->id }}" class="btn btn-primary btn-sm w-100">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        No products found. Please try adjusting your filters.
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
