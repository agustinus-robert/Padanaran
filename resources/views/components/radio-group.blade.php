@props([
    'name',
    'options' => [],
    'selected' => null,
    'required' => false,
])

@foreach($options as $option)
    <label class="{{ $option['wrapper_class'] ?? '' }}">
        <input type="radio"
               name="{{ $name }}"
               value="{{ $option['value'] }}"
               class="form-check-input {{ $option['input_class'] ?? '' }}"
               autocomplete="off"
               @if($selected == $option['value']) checked @endif
               @if($required) required @endif

               {{-- Extra attributes --}}
               @if(isset($option['attributes']))
                   @foreach($option['attributes'] as $attr => $val)
                       {{ $attr }}="{{ $val }}"
                   @endforeach
               @endif
        >

        {!! $option['label'] !!}
    </label>
@endforeach

@error($name)
    <small class="text-danger d-block">{{ $message }}</small>
@enderror