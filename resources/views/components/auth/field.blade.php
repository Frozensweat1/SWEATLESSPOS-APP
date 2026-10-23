@props(['name', 'label', 'type' => 'text', 'id' => null, 'value' => null])
@php
    $id = $id ?: $name;
    $invalid = $errors->has($name);
@endphp
<div>
    <label class="label" for="{{ $id }}">{{ $label }}</label>
    @if ($type === 'password')
        <div class="pw-wrap">
            <input id="{{ $id }}" name="{{ $name }}" type="password"
                {{ $attributes->merge(['class' => 'input' . ($invalid ? ' is-invalid' : '')]) }}
                @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
            <button type="button" class="pw-toggle" data-toggle-password="{{ $id }}" aria-pressed="false" aria-label="Toggle password visibility">Show</button>
        </div>
    @else
        <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" @if ($value !== null) value="{{ $value }}" @endif
            {{ $attributes->merge(['class' => 'input' . ($invalid ? ' is-invalid' : '')]) }}
            @if ($invalid) aria-invalid="true" aria-describedby="{{ $id }}-error" @endif>
    @endif
    @error($name)
        <div class="error" id="{{ $id }}-error" role="alert">{{ $message }}</div>
    @enderror
</div>
