@props([
    'name',
    'id' => null,
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'multiple' => false,
])

@php
$id = $id ?? $name;
$selectName = $multiple ? $name.'[]' : $name;
$error = $errors->has($name) ? 'is-invalid' : '';
$values = $multiple ? (array) $value : [$value];
@endphp

<div style="position:relative;width:100%;">
    <select
        name="{{ $selectName }}"
        id="{{ $id }}"
        {{ $attributes->merge(['class' => $error]) }}
        @if($required) required @endif
        @if($multiple) multiple @endif
        style="
            appearance:none;
            width:100%;
            background:#fff;
            color:#000;
            padding:0.35rem 1.5rem 0.25rem 0.5rem;
            border:1px solid #ced4da;
            border-radius:0.375rem;
        "
    >
        @if($placeholder && !$multiple)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif

        @foreach($options as $opt)
            <option
                value="{{ $opt['value'] }}"
                @selected(in_array($opt['value'], $values))
            >
                {{ $opt['label'] }}
            </option>
        @endforeach
    </select>

    @unless($multiple)
        <span style="
            position:absolute;
            top:50%;
            right:0.5rem;
            transform:translateY(-50%);
            pointer-events:none;
            color:#000;
            font-size:0.8rem;
        ">▼</span>
    @endunless

    @error($name)
        <small class="invalid-feedback d-block">{{ $message }}</small>
    @enderror
</div>
