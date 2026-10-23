<div>
    <section class="hero hero--sm" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <h1>{{ $page->title ?? 'About Us' }}</h1>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($page && $page->content)
                <div class="prose">{!! $page->content !!}</div>
            @else
                <p class="muted">Our story is coming soon.</p>
            @endif
        </div>
    </section>

    @if ($founderName)
        <section class="section section--alt">
            <div class="container split">
                <div>
                    <span class="eyebrow">Meet the founder</span>
                    <h2>{{ $founderName }}</h2>
                    @if ($founderTitle)<p class="muted"><strong>{{ $founderTitle }}</strong></p>@endif
                    @if ($founderBio)<p>{!! nl2br(e($founderBio)) !!}</p>@endif
                </div>
                @if ($founderImage)
                    <img class="founder-img" src="{{ asset('storage/' . $founderImage) }}" alt="{{ $founderName }}" loading="lazy" decoding="async">
                @endif
            </div>
        </section>
    @endif
</div>
