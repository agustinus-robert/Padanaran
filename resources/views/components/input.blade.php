@props([
    'disabled' => false,
    'value' => null,
])

<input
    type="text"
    value="{{ old($attributes->get('name'), $value) }}"
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge([
        'class' => 'form-control border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'
    ]) !!}
/>
