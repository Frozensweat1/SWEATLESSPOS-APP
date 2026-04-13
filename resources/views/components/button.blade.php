@props([
    'type' => 'button',
    'variant' => 'default',
    'size' => 'md',
    'loading' => false,
    'disabled' => false,
    'icon' => null,
    'wireTarget' => null,
    'wireConfirm' => null,
])

@php
    $variants = [
        'primary' => 'btn-primary',
        'secondary' => 'btn-secondary',
        'success' => 'btn-success',
        'danger' => 'btn-danger',
        'warning' => 'btn-warning',
        'info' => 'btn-info',
        'light' => 'btn-light',
        'dark' => 'btn-dark',
        'default' => 'btn-default',
    ];

    $sizes = [
        'sm' => 'btn-sm',
        'md' => '',
        'lg' => 'btn-lg',
    ];

    $variantClass = $variants[$variant] ?? 'btn-default';
    $sizeClass = $sizes[$size] ?? '';
@endphp

<button type="{{ $type }}" {{ $disabled || $loading ? 'disabled' : '' }}
    @if ($wireTarget) wire:loading.attr="disabled"
        wire:loading.class="opacity-50"
        wire:target="{{ $wireTarget }}" @endif
    @if ($wireConfirm) wire:confirm="{{ $wireConfirm }}" @endif
    {{ $attributes->merge(['class' => "btn {$variantClass} {$sizeClass}"]) }}>
    @if ($loading || $wireTarget)
        <span @if ($wireTarget) wire:loading.remove wire:target="{{ $wireTarget }}" @endif>
            @if ($icon)
                <i class="{{ $icon }}"></i>
            @endif
            {{ $slot }}
        </span>
        @if ($wireTarget)
            <span wire:loading wire:target="{{ $wireTarget }}">
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        @else
            <span wire:loading>
                <i class="fas fa-spinner fa-spin"></i>
            </span>
        @endif
    @else
        @if ($icon)
            <i class="{{ $icon }}"></i>
        @endif
        {{ $slot }}
    @endif
</button>
