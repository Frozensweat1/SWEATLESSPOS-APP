<div x-data="{ idx: null, imgs: @js($images->map(fn ($i) => ['src' => asset('storage/' . $i->image_path), 'title' => $i->title])->values()),
        open(i) { this.idx = i }, close() { this.idx = null },
        step(d) { this.idx = (this.idx + d + this.imgs.length) % this.imgs.length } }"
     @keydown.escape.window="close()" @keydown.arrow-right.window="idx !== null && step(1)" @keydown.arrow-left.window="idx !== null && step(-1)">
    <section class="hero hero--sm" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <nav class="crumbs" aria-label="Breadcrumb"><a href="{{ route('website.gallery') }}" wire:navigate>Gallery</a><span>/</span><span>{{ $album->name }}</span></nav>
            <h1>{{ $album->name }}</h1>
            @if ($album->description)<p class="lead">{{ $album->description }}</p>@endif
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($images->isEmpty())
                <div class="empty"><x-site.icon name="image" :size="48" /><p>This album has no photos yet.</p></div>
            @else
                <div class="masonry">
                    @foreach ($images as $i => $img)
                        <button type="button" @click="open({{ $i }})" aria-label="View {{ $img->title ?: 'photo ' . ($i + 1) }}">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->title }}" loading="lazy" decoding="async">
                        </button>
                    @endforeach
                </div>
            @endif
            <p style="margin-top:1.5rem"><a href="{{ route('website.gallery') }}" wire:navigate class="btn btn--ghost"><x-site.icon name="arrow-left" :size="18" /> All albums</a></p>
        </div>
    </section>

    <div class="lightbox" x-show="idx !== null" x-cloak x-transition.opacity role="dialog" aria-modal="true" aria-label="Photo viewer" @click.self="close()">
        <button type="button" class="close" @click="close()" aria-label="Close"><x-site.icon name="x" /></button>
        <figure style="margin:0;text-align:center">
            <template x-if="idx !== null">
                <div>
                    <img :src="imgs[idx].src" :alt="imgs[idx].title || ''">
                    <figcaption x-text="imgs[idx].title"></figcaption>
                </div>
            </template>
        </figure>
        <template x-if="imgs.length > 1">
            <div>
                <button type="button" class="icon-btn" style="position:absolute;left:1rem;top:50%" @click="step(-1)" aria-label="Previous"><x-site.icon name="chevron-left" /></button>
                <button type="button" class="icon-btn" style="position:absolute;right:1rem;top:50%" @click="step(1)" aria-label="Next"><x-site.icon name="chevron-right" /></button>
            </div>
        </template>
    </div>
</div>
