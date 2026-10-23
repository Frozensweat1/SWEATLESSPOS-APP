@props(['rating' => 5])
<span class="stars" role="img" aria-label="{{ (int) $rating }} out of 5 stars">
    @for ($i = 1; $i <= 5; $i++)
        <x-site.icon name="star" :size="16" :class="'icon ' . ($i <= $rating ? '' : 'off')" style="{{ $i <= $rating ? 'fill:currentColor' : '' }}" />
    @endfor
</span>
