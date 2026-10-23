<div>
    <section class="section" style="padding-bottom:1rem">
        <div class="container">
            <nav class="crumbs muted" aria-label="Breadcrumb">
                <a href="{{ route('website.products') }}" wire:navigate>Products</a>
                @if ($product->category)
                    <span>/</span>
                    <a href="{{ route('website.products', ['category' => $product->category_id]) }}" wire:navigate>{{ $product->category->name }}</a>
                @endif
                <span>/</span><span aria-current="page">{{ $product->name }}</span>
            </nav>

            <div class="split split--detail">
                <div class="card media" style="aspect-ratio:1">
                    <x-site.icon name="package" :size="72" />
                    @if ($image)<img src="{{ $image }}" alt="{{ $product->name }}" decoding="async" onerror="this.remove()">@endif
                </div>

                <div>
                    @if ($product->category)<span class="eyebrow">{{ $product->category->name }}</span>@endif
                    <h1 style="font-size:clamp(1.8rem,4vw,2.5rem)">{{ $product->name }}</h1>
                    <p class="price" style="font-size:1.8rem">{{ config('app.currency_symbol', 'GHS') }} {{ number_format($product->price, 2) }}</p>

                    @if ($product->stock_status === 'out')
                        <span class="badge badge--out">Out of stock</span>
                    @elseif ($product->stock_status === 'low')
                        <span class="badge badge--warn">Low stock</span>
                    @else
                        <span class="badge badge--ok"><x-site.icon name="check" :size="14" /> In stock</span>
                    @endif

                    @if ($product->description)
                        <div class="prose" style="margin-top:1.25rem">{!! nl2br(e($product->description)) !!}</div>
                    @endif

                    <div style="display:flex;flex-wrap:wrap;gap:.75rem;margin-top:1.5rem">
                        @if ($phone)
                            <a class="btn btn--lg" href="tel:{{ preg_replace('/[^\d+]/', '', $phone) }}"><x-site.icon name="phone" :size="20" /> Call to order</a>
                        @endif
                        @if ($whatsapp)
                            <a class="btn btn--wa btn--lg" target="_blank" rel="noopener noreferrer"
                               href="https://wa.me/{{ $whatsapp }}?text={{ rawurlencode('Hi, I am interested in ' . $product->name) }}"><x-site.icon name="message-circle" :size="20" /> WhatsApp</a>
                        @endif
                        <a class="btn btn--ghost btn--lg" href="{{ route('website.contact') }}" wire:navigate>Contact us</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedProducts->isNotEmpty())
        <section class="section">
            <div class="container">
                <div class="section-head"><h2>You may also like</h2></div>
                <div class="grid">
                    @foreach ($relatedProducts as $related)
                        <x-site.product-card :product="$related" wire:key="rp-{{ $related->id }}" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
</div>
