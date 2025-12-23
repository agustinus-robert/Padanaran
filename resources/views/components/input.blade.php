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
    type="{{ $type == 'number' ? 'text' : $type }}"
    value="{{ $inputValue }}"
    {{ $disabled ? 'disabled' : '' }}

    {{-- Tambahkan Validasi Jika Type adalah Number --}}
    @if($type === 'number')
        pattern="[0-9]*"
        inputmode="numeric"
        onkeypress="return event.charCode >= 48 && event.charCode <= 57"
        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
    @endif

    {!! $attributes->merge(['class' => $form]) !!}
/>
