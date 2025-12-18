@props([
    'label' => null,
    'required' => false,
    'labelCol' => '3',
    'slotCol' => '12',
    'isForm' => false,
    'isRow' => false
])

@if($isForm == false)
    <div class="input-group input-group-outline row mb-3">
@else
    <div class="form-group mb-3">
@endif

    @if ($label)
        <label class="col-md-{{ $labelCol }} col-form-label">
            {{ $label }}
            @if($required)
                <span class="text-danger">*</span>
            @endif
        </label>
    @endif


    <div class="col-md-12">

        @if($isRow == true)
            <div class="row">
                {{ $slot }}
            </div>
        @else
            {{ $slot }}
        @endif
    </div>


</div>
