@props([
    'type' => 'button',
    'variant' => 'dark',  // dark, primary, success, danger, etc.
])

@php
    $base = "btn bg-gradient-$variant";
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base]) }}>
    {{ $slot }}
</button>
