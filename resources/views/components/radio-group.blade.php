@props([
    'name',
    'options' => [],
    'selected' => null,
    'required' => false, // <-- default value
])

<div class="btn-group btn-group-toggle" data-toggle="buttons">
    @foreach($options as $value => $label)
        <label class="btn btn-outline-secondary @if($selected == $value) active @endif">
            <input type="radio"
                   name="{{ $name }}"
                   value="{{ $value }}"
                   autocomplete="off"
                   @if($selected == $value) checked @endif
                   @if($required) required @endif> {{ $label }}
        </label>
    @endforeach
</div>

@error($name)
    <small class="text-danger d-block">{{ $message }}</small>
@enderror
