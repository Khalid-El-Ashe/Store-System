{{-- i need to add the default value --}}
@props([
'type' => 'text',
'name' => '',
'placeholder' => '',
'value' => '',
'label' => false,
])

@if ($label)
<label for="{{ $name }}">{{ $label }}</label>
@endif

<input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
    value="{{ old($name, $value) }}" autocomplete="{{ $name }}" {{ $attributes->class(['form-control', 'is-invalid' =>
$errors->has($name)]) }}
>

@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror
