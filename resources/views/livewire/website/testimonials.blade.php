<div>
    <section class="hero hero--sm" style="--hero-bg: {{ $heroBackgroundUrl }}">
        <div class="container">
            <h1>{{ $page->title ?? 'Customer Reviews' }}</h1>
            <p class="lead">Read what our customers have to say.</p>
        </div>
    </section>

    <section class="section">
        <div class="container">
            @if ($page && $page->content)<div class="prose" style="margin-bottom:2rem">{!! $page->content !!}</div>@endif

            @if ($testimonials->isEmpty())
                <div class="empty"><x-site.icon name="star" :size="48" /><p>No reviews yet.</p></div>
            @else
                <div class="grid grid--wide">
                    @foreach ($testimonials as $t)
                        <figure class="card card-body" style="margin:0" wire:key="tt-{{ $t->id }}">
                            <x-site.stars :rating="$t->rating" />
                            <blockquote class="quote" style="margin:.75rem 0 1rem">&ldquo;{{ $t->testimonial_text }}&rdquo;</blockquote>
                            <figcaption style="display:flex;gap:.75rem;align-items:center">
                                @if ($t->author_image)
                                    <img class="avatar" src="{{ asset('storage/' . $t->author_image) }}" alt="" loading="lazy" decoding="async" width="48" height="48">
                                @else
                                    <span class="avatar"><x-site.icon name="user" :size="22" /></span>
                                @endif
                                <span><strong>{{ $t->author_name }}</strong><br><span class="muted" style="font-size:.85rem">{{ $t->author_title }}</span></span>
                            </figcaption>
                        </figure>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
