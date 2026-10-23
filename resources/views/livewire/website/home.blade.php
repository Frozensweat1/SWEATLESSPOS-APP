<div>
    <section class="hero" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <h1>{{ $siteName }}</h1>
            <p class="lead">{{ $tagline }}</p>
            <div class="actions">
                <a href="{{ route('website.products') }}" wire:navigate class="btn btn--light btn--lg"><x-site.icon name="shopping-bag" :size="20" /> Browse products</a>
                <a href="{{ route('website.contact') }}" wire:navigate class="btn btn--outline-light btn--lg">Contact us</a>
                @if ($whatsapp)
                    <a href="https://wa.me/{{ $whatsapp }}" target="_blank" rel="noopener noreferrer" class="btn btn--wa btn--lg"><x-site.icon name="message-circle" :size="20" /> WhatsApp</a>
                @endif
            </div>
        </div>
    </section>

    @if ($page && $page->content)
        <section class="section">
            <div class="container prose">{!! $page->content !!}</div>
        </section>
    @endif

    @if ($featuredProducts->isNotEmpty())
        <section class="section {{ $page && $page->content ? 'section--alt' : '' }}">
            <div class="container">
                <div class="section-head">
                    <div><span class="eyebrow">Shop</span><h2>Featured products</h2></div>
                    <a href="{{ route('website.products') }}" wire:navigate class="btn btn--ghost">View all <x-site.icon name="arrow-right" :size="18" /></a>
                </div>
                <div class="grid">
                    @foreach ($featuredProducts as $product)
                        <x-site.product-card :product="$product" wire:key="fp-{{ $product->id }}" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if ($aboutPage && $aboutPage->content)
        <section class="section">
            <div class="container split">
                <div>
                    <span class="eyebrow">About us</span>
                    <h2>{{ $aboutPage->title }}</h2>
                    <p class="muted">{{ \Illuminate\Support\Str::limit(strip_tags($aboutPage->content), 420) }}</p>
                    <a href="{{ route('website.about') }}" wire:navigate class="btn">Learn more <x-site.icon name="arrow-right" :size="18" /></a>
                </div>
                @if ($aboutImage)
                    <img src="{{ asset('storage/' . $aboutImage) }}" alt="" class="card" loading="lazy" decoding="async" style="width:100%;aspect-ratio:4/3;object-fit:cover">
                @endif
            </div>
        </section>
    @endif

    @if ($galleryAlbums->isNotEmpty())
        <section class="section section--alt">
            <div class="container">
                <div class="section-head">
                    <div><span class="eyebrow">Gallery</span><h2>Latest albums</h2></div>
                    <a href="{{ route('website.gallery') }}" wire:navigate class="btn btn--ghost">All albums <x-site.icon name="arrow-right" :size="18" /></a>
                </div>
                <div class="grid">
                    @foreach ($galleryAlbums as $album)
                        @php $cover = $album->cover_image ?: optional($album->activeImages->first())->image_path; @endphp
                        <a href="{{ route('website.gallery.detail', $album->slug) }}" wire:navigate class="card card--link" wire:key="al-{{ $album->id }}">
                            <div class="media">
                                <x-site.icon name="image" :size="40" />
                                @if ($cover)<img src="{{ asset('storage/' . $cover) }}" alt="" loading="lazy" decoding="async" onerror="this.remove()">@endif
                            </div>
                            <div class="card-body">
                                <h3 style="margin:0">{{ $album->name }}</h3>
                                <span class="muted">{{ $album->activeImages->count() }} {{ \Illuminate\Support\Str::plural('photo', $album->activeImages->count()) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section">
        <div class="container">
            <div class="section-head">
                <div><span class="eyebrow">Reviews</span><h2>What customers say</h2></div>
                <a href="{{ route('website.testimonials') }}" wire:navigate class="btn btn--ghost">Read all <x-site.icon name="arrow-right" :size="18" /></a>
            </div>
            @livewire('website.testimonials-carousel')
        </div>
    </section>
</div>
