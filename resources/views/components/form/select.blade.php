@props([
'name',
'label'=> null,
'options' => [],
'selected' => null,
])

<label>{{ $label }}</label>
<select name="{{ $name }}" id="{{ $name }}" {{ $attributes->class(['form-control', 'form-select', 'is-invalid' =>
    $errors->has($name)]) }}>

    @foreach ($options as $option => $text)
    <option value="{{ $option }}" @selected($option==$selected)>{{ $text }}</option>
    @endforeach

</select>

@error($name)
<div class="invalid-feedback">{{ $message }}</div>
@enderror

{{--
<x-form.validation-feedback :name="$name" /> --}}