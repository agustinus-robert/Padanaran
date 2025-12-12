@props([
    'for' => null,
    'value' => null,
    'col' => null, // contoh: "3", "3 6 12", "3-lg 4-md"
])

@php
    $theme = config('theme.default');

    $classes = "col-form-label";

    if ($col) {
        $colParts = explode(' ', $col);

        foreach ($colParts as $part) {
            if (str_contains($part, '-')) {
                [$size, $bp] = explode('-', $part);
                $classes = "col-$bp-$size " . $classes;
            } else {
                $classes = "col-$part " . $classes;
            }
        }
    }
@endphp

<label
    @if($for) for="{{ $for }}" @endif
    {{ $attributes->merge(['class' => $classes]) }}
>
    {{ $value ?? $slot }}
</label>
