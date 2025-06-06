{{-- i need to add the default value --}}
@props([
'type'=> 'text',
'name'=> '',
'placeholder'=> '',
'value'=>'',
'label'=> false
])

<label for="">{{$label}}</label>
<input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}" placeholder="{{ $placeholder }}"
    value="{{ old($name, $value) }}" @class(['form-control', 'is-invalid'=>
$errors->has($name)])
{{ $attributes }}
>

@error($name)
<div class="invalid-feedback">
    {{ $message }}
</div>
@enderror
