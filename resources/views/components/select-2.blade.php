@props([
    'name',
    'id' => null,
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
])

@php
    $id = $id ?? $name;
    $errorClass = $errors->has($name) ? 'is-invalid' : '';
@endphp

<select
    name="{{ $name }}"
    id="{{ $id }}"
    {{ $attributes->merge(['class' => "form-control select-2 $errorClass"]) }}
    @if($required) required @endif
>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($options as $opt)
        <option value="{{ $opt['value'] }}"
            @if($value == $opt['value']) selected @endif>
            {{ $opt['label'] }}
        </option>
    @endforeach
</select>

@error($name)
    <small class="invalid-feedback d-block">{{ $message }}</small>
@enderror
