@props(['product'])
@php
    $img = $product->image && ! str_ends_with($product->image, 'noimage.png') ? asset('storage/' . $product->image) : null;
    $status = $product->stock_status;
@endphp
<a href="{{ route('website.product-detail', $product->id) }}" wire:navigate {{ $attributes->merge(['class' => 'card card--link']) }}>
    <div class="media">
        <x-site.icon name="package" :size="40" />
        @if ($img)
            <img src="{{ $img }}" alt="{{ $product->name }}" loading="lazy" decoding="async" onerror="this.remove()">
        @endif
        @if ($status === 'out')
            <span class="badge badge--out">Out of stock</span>
        @elseif ($status === 'low')
            <span class="badge badge--warn">Low stock</span>
        @endif
    </div>
    <div class="card-body">
        @if ($product->category)<span class="muted" style="font-size:.8rem">{{ $product->category->name }}</span>@endif
        <h3 style="margin:.15rem 0 .4rem">{{ $product->name }}</h3>
        <span class="price">{{ config('app.currency_symbol', 'GHS') }} {{ number_format($product->price, 2) }}</span>
    </div>
</a>
