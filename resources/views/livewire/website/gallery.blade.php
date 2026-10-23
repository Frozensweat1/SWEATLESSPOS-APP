<div>
    <section class="hero hero--sm" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <h1>Gallery</h1>
            <p class="lead">Moments and highlights from our business.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($albums->isEmpty())
                <div class="empty"><x-site.icon name="image" :size="48" /><p>No albums yet. Check back soon.</p></div>
            @else
                <div class="grid">
                    @foreach ($albums as $album)
                        @php $cover = $album->cover_image ?: optional($album->activeImages->first())->image_path; @endphp
                        <a href="{{ route('website.gallery.detail', $album->slug) }}" wire:navigate class="card card--link" wire:key="album-{{ $album->id }}">
                            <div class="media">
                                <x-site.icon name="image" :size="40" />
                                @if ($cover)<img src="{{ asset('storage/' . $cover) }}" alt="" loading="lazy" decoding="async" onerror="this.remove()">@endif
                            </div>
                            <div class="card-body">
                                <h3 style="margin:0">{{ $album->name }}</h3>
                                @if ($album->description)<p class="muted" style="margin:.25rem 0 0">{{ \Illuminate\Support\Str::limit($album->description, 90) }}</p>@endif
                                <span class="badge" style="margin-top:.5rem">{{ $album->activeImages->count() }} {{ \Illuminate\Support\Str::plural('photo', $album->activeImages->count()) }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
