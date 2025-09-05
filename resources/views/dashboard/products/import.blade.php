@extends('dashboard.index')
@section('title', 'Import Product')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Import</li>
@endsection
@section('content')

{{-- enctype="multipart/form-data" this is becuase you use the file --}}
<form action="{{route('dashboard.products.import.store')}}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="form-group">
        <x-form.input label="Products Count" class="form-control-lg" role="input" name="count" />
    </div>

    <button type="submit" class="btn btn-outline-success">
        Start Import
    </button>
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
