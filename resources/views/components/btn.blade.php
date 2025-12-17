@props([
    'type' => 'button',
    'variant' => 'dark',  // dark, primary, success, danger, etc.
    'start' => null,
    'end' => null
])

@php
    $base = "btn bg-gradient-$variant";
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $base]) }}>
    {{-- Start slot --}}
    @if($start)
        {!! $start !!}
    @endif

    {{-- Main slot --}}
    {{ $slot }}

    {{-- End slot --}}
    @if($end)
        {!! $end !!}
    @endif
</button>
