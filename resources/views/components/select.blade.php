@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'options' => [],
    'placeholder' => null,
    'required' => false,
    'multiple' => false,
])

@php
    $selectName = $name
        ? ($multiple ? $name.'[]' : $name)
        : null;

    $id = $id ?? $name;

    $error = $name && $errors->has($name) ? 'is-invalid' : '';

    $values = $multiple
        ? (array) old($name, $value)
        : [old($name, $value)];
@endphp

<div style="position:relative;width:100%;">
    <select
        @if($selectName) name="{{ $selectName }}" @endif
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->merge(['class' => $error]) }}
        @if($required && $name) required @endif
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
            <option value="">{{ $placeholder }}</option>
        @endif

        @foreach($options as $opt)
            <option
                value="{{ $opt['value'] }}"
                @selected(in_array($opt['value'], $values, true))
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

    @if($name)
        @error($name)
            <small class="invalid-feedback d-block">{{ $message }}</small>
        @enderror
    @endif
</div>
