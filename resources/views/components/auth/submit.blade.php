@props(['type' => 'submit'])
<button type="{{ $type }}" {{ $attributes->merge(['class' => 'btn btn--block btn--lg']) }}>
    <span class="spin" aria-hidden="true"></span>
    <span>{{ $slot }}</span>
</button>
