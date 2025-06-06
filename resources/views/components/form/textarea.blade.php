@props([
'type' => 'text',
'name' => '',
'placeholder' => '',
'value' => '',
'label' => false, {{-- صححت lable إلى label --}}
])

<label for="{{ $name }}">{{ $label }}</label>
<textarea class="form-control form-control-lg @error($name) is-invalid @enderror" name="{{ $name }}" id="{{ $name }}"
    placeholder="{{ $placeholder }}">{{ old($name, $value) }}</textarea>

@error($name)
<div class="invalid-feedback">{{ $message }}</div>
@enderror