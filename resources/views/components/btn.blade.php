@props([
    'type' => 'button',
    'variant' => 'dark',  // dark, primary, success, danger, etc.
])

@php
    // Base class
    $base = "btn bg-gradient-$variant";

    // Merge extra class from attributes
    $class = $attributes->merge(['class' => $base])->get('class');
@endphp

<button type="{{ $type }}" class="{{ $class }}">
    {{ $slot }}
</button>
