{{-- resources/views/components/select.blade.php --}}
@props([
    'name','id'=>null,'value'=>null,'options'=>[],'placeholder'=>null,'required'=>false
])

@php
$id = $id ?? $name;
$error = $errors->has($name) ? 'is-invalid' : '';
@endphp

<div style="position:relative;width:100%;">
    <select name="{{ $name }}" id="{{ $id }}" {{ $attributes->merge(['class'=>$error]) }}
        @if($required) required @endif
        style="appearance:none;width:100%;background:#fff;color:#000;padding:0.35rem 1.5rem 0.25rem 0.5rem;border:1px solid #ced4da;border-radius:0.375rem;"
    >
        @if($placeholder)
            <option value="" disabled selected>{{ $placeholder }}</option>
        @endif
        @foreach($options as $opt)
            <option value="{{ $opt['value'] }}" @selected($value==$opt['value'])>{{ $opt['label'] }}</option>
        @endforeach
    </select>

    <span style="position:absolute;top:50%;right:0.5rem;transform:translateY(-50%);pointer-events:none;color:#000;font-size:0.8rem;">▼</span>

    @error($name)<small class="invalid-feedback d-block">{{ $message }}</small>@enderror
</div>
