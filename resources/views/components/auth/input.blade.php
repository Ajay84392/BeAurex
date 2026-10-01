@props(['name', 'label', 'id' => null, 'type' => 'text', 'placeholder' => '', 'value' => null, 'autocomplete' => null])

@php($id ??= $name.'Field')

<div>
    <label for="{{ $id }}" class="auth-label">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}" name="{{ $name }}" value="{{ $value ?? old($name) }}" placeholder="{{ $placeholder }}"
        @if($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
        {{ $attributes->merge(['class' => 'auth-input'.($errors->has($name) ? ' border-red-400' : '')]) }}>
    {{ $slot }}
    <x-auth.error :name="$name" />
</div>
