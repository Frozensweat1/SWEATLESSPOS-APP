<div class="carousel" x-data="{
        scroll(dir) { const t = $refs.track; t.scrollBy({ left: dir * (t.firstElementChild?.offsetWidth ?? 300) + dir * 20, behavior: 'smooth' }); }
    }">
    @if ($testimonials->isEmpty())
        <p class="muted">No reviews yet.</p>
    @else
        <div class="track" x-ref="track" tabindex="0" role="region" aria-label="Customer reviews">
            @foreach ($testimonials as $t)
                <figure class="card card-body" style="margin:0" wire:key="t-{{ $t->id }}">
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
        @if ($testimonials->count() > 1)
            <div class="carousel-nav" style="margin-top:1rem">
                <button type="button" class="icon-btn" @click="scroll(-1)" aria-label="Previous review"><x-site.icon name="chevron-left" /></button>
                <button type="button" class="icon-btn" @click="scroll(1)" aria-label="Next review"><x-site.icon name="chevron-right" /></button>
            </div>
        @endif
    @endif
</div>
