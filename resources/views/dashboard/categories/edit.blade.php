@extends('dashboard.index')
@section('title', 'Edit Category')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')

<form action="{{route('categories.update', $category->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('dashboard.form._edit')
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