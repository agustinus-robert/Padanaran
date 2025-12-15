@props([
    'label' => null,
    'required' => false,
    'labelCol' => '3',
    'slotCol' => '7',
    'isRow' => false,
    'isForm' => false
])

@if($isForm == false)
    <div class="input-group input-group-outline row mb-3">
@else
    <div class="form-group mb-3">
@endif
    @if ($label)
        <label class="{{ $labelCol ? "col-md-$labelCol col-form-label" : '' }}">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif

    @if($isRow == false)
        <div class="{{ $slotCol ? "col-md-$slotCol" : '' }}">
            {{ $slot }}
        </div>
    @else
        {{ $slot }}
    @endif

</div>
