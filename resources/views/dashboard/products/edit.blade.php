@extends('dashboard.index')
@section('title', 'Edit Product')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Edit</li>
@endsection
@section('content')

<form action="{{route('dashboard.products.update', $product->id)}}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @include('dashboard.products._form',[
    'button_label' => 'Update'
    ])
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