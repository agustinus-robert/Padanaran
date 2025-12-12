@props([
    'size' => null, // contoh: "3", "3 6 12", "4-lg 6-md 12-sm"
])

@php
    $classes = "";

    if ($size) {
        $parts = explode(' ', $size);

        foreach ($parts as $part) {
            // jika ada breakpoint: contoh "4-lg"
            if (str_contains($part, '-')) {
                [$col, $bp] = explode('-', $part); // 4-lg => [4, lg]
                $classes .= " col-$bp-$col";
            } else {
                // tanpa breakpoint: contoh "3"
                $classes .= " col-$part";
            }
        }
    }
@endphp

<div {{ $attributes->merge(['class' => trim($classes)]) }}>
    {{ $slot }}
</div>
