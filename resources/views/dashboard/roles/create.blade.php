@extends('dashboard.index')
@section('title', 'Create Role')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Roles</li>
@endsection
@section('content')

{{-- enctype="multipart/form-data" this is becuase you use the file --}}
<form action="{{route('dashboard.roles.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    @include('dashboard.roles.form._form')
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