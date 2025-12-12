@props([
    'label' => null,
    'required' => false,
])

<div class="input-group input-group-outline row mb-3">

    @if ($label)
        <label class="col-md-3 col-form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    <div class="col-md-7">
        {{ $slot }}
    </div>

</div>
