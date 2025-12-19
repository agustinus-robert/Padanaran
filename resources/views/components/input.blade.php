@props([
    'disabled' => false,
    'value' => null,
    'type' => 'text',
    'size' => 'md', // sm, md, lg
])

@php
    $inputValue = is_array($value) ? '' : old($attributes->get('name') ?? '', $value);

    // Tentukan kelas ukuran
    $sizes = [
        'sm' => 'px-1 py-2 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];

    // Pilih kelas sesuai type
    $form = $type === 'checkbox' ? 'form-check-input' : 'form-control ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<input
    type="{{ $type }}"
    value="{{ $inputValue }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => $form
    ]) !!}
/>
