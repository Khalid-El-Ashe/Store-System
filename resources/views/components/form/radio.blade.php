@props([
'name',
'options',
'checked'=> ''
])
@foreach ($options as $value => $text)
<div class="icheck-primary d-inline">
    <input type="radio" id="status_active" name="{{$name}}" value="{{$value}}" @checked(old($name, $checked)==$value)>
    <label for="status_active">
        {{$text}}
    </label>
</div>
@endforeach