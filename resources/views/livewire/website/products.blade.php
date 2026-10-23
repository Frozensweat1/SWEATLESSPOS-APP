<div>
    <section class="hero hero--sm" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <h1>Our products</h1>
            <p class="lead">Find what you need — search, filter and sort below.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="toolbar">
                <div class="field">
                    <x-site.icon name="search" :size="18" />
                    <input type="search" class="input" placeholder="Search products…" aria-label="Search products" wire:model.live.debounce.300ms="search">
                </div>
                <select class="select" style="flex:0 1 200px" aria-label="Sort products" wire:model.live="sortBy">
                    <option value="name">Name (A–Z)</option>
                    <option value="price_asc">Price: low to high</option>
                    <option value="price_desc">Price: high to low</option>
                    <option value="newest">Newest</option>
                </select>
            </div>

            @if ($categories->isNotEmpty())
                <div class="chips" style="margin-bottom:1.5rem" role="group" aria-label="Categories">
                    <button type="button" class="chip" aria-pressed="{{ $category_id === '' ? 'true' : 'false' }}" wire:click="setCategory('')">All</button>
                    @foreach ($categories as $cat)
                        <button type="button" class="chip" aria-pressed="{{ (string) $category_id === (string) $cat->id ? 'true' : 'false' }}" wire:click="setCategory({{ $cat->id }})" wire:key="cat-{{ $cat->id }}">{{ $cat->name }}</button>
                    @endforeach
                </div>
            @endif

            <div wire:loading.class="is-loading" wire:target="search,sortBy,setCategory">
                @if ($products->isEmpty())
                    <div class="empty">
                        <x-site.icon name="package" :size="48" />
                        <p>No products found. Try a different search or category.</p>
                    </div>
                @else
                    <div class="grid">
                        @foreach ($products as $product)
                            <x-site.product-card :product="$product" wire:key="p-{{ $product->id }}" />
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($hasMore)
                <div class="center" style="margin-top:2rem">
                    <button type="button" class="btn btn--ghost" wire:click="loadMore" wire:loading.attr="disabled" wire:target="loadMore">
                        <span wire:loading.remove wire:target="loadMore">Load more</span>
                        <span wire:loading wire:target="loadMore">Loading…</span>
                    </button>
                </div>
            @endif
        </div>
    </section>
</div>
