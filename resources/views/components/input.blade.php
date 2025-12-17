@props([
    'disabled' => false,
    'value' => null,
    'type' => 'text',
])

@php
    $inputValue = is_array($value) ? '' : old($attributes->get('name') ?? '', $value);
@endphp

<input
    type="{{ $type }}"
    value="{{ $inputValue }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'form-control border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm py-2'
    ]) !!}
/>
