@extends('dashboard.index')
@section('title', 'Edit Profile')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Edit Profile</li>
@endsection
@section('content')

<form action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('patch')
    <div class="form-row">
        <div class="col-md-6">
            <x-form.input name="first_name" label="Frist Name" :value="$user->profile->first_name" />
        </div>
        <div class="col-md-6">
            <x-form.input name="last_name" label="Last Name" :value="$user->profile->last_name" />
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-6">
            <x-form.input name="birth_day" type="date" label="Birthday" :value="$user->profile->birth_day" />
        </div>
        <div class="col-md-6">
            <x-form.radio name="gender" label="Gender" :options="['male' => 'Male', 'female' => 'Female']"
                :checked="$user->profile->gender" />
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-6">
            <x-form.input name="street_address" label="Street Address" :value="$user->profile->street_address" />
        </div>
        <div class="col-md-6">
            <x-form.input name="city" label="City" :value="$user->profile->city" />
        </div>
        <div class="col-md-6">
            <x-form.input name="state" label="State" :value="$user->profile->state" />
        </div>
    </div>

    <div class="form-row">
        <div class="col-md-6">
            <x-form.input name="postal_code" label="Postal Code" :value="$user->profile->prostal_code" />
        </div>
        <div class="col-md-6">
            <x-form.select name="country" label="Country" :options="$countries" :selected="$user->profile->country" />
        </div>
        <div class="col-md-6">
            <x-form.select name="local" label="Local" :options="$locales" :selected="$user->profile->local" />
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
    </div>
</form>

@endsection
@section('scripts')
<script>
    document.getElementById("image").addEventListener("change", function (event) {
    const reader = new FileReader();
    const file = event.target.files[0];

    if (file) {
    reader.onload = function (e) {
    const preview = document.getElementById("preview-image");
    preview.src = e.target.result;
    preview.style.display = "block";
    };
    reader.readAsDataURL(file);
    }
    });
</script>
@endsection