@extends('dashboard.index')

@section('styles')
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@endsection

@section('title', 'Products')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Products</li>
@endsection
@section('content')

<div class="mb-5">
    @can('create', 'App\\Models\Product')
    <a href="{{route('dashboard.products.create')}}" class="btn bg-olive active">Add Product</a>
    @endcan
    {{-- <a href="{{route('products.trash')}}" class="btn bg-olive active">Trashes products</a> --}}
</div>

{{-- @component('components.alert') --}}
<x-alert type="success" />
<!--- this component i added it --->

<form action="{{URL::current()}}" method="get" class="d-flex justify-content-between mb-4 mx-2">

    <x-form.input name="name" placeholder="Name" :value="request('name')" />
    <select name="status" class="form-control mx-2">
        <option value="">All</option>
        <option value="active" @selected(request('status'=='active' ))>Active</option>
        <option value="archived" @selected(request('status'=='archived' ))>Archived</option>
    </select>
    <span class="input-group-append">
        <button type="submit" class="btn btn-info btn-flat">Filter</button>
    </span>
    {{-- <button class="btn btn-primary"></button> --}}
</form>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created At</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($products as $product)
        <tr>
            <td>{{$product->id}}</td>
            <td>
                @if ($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" class="rounded-circle" width="50" height="50">
                @else
                <img src="{{ asset('storage/broken-image.png')}}" class="rounded-circle" width="50" height="50">
                @endif
            </td>

            <td>{{$product->name}}</td>

            <td>{{$product->category->name}}</td>
            <td>{{$product->store->name ?? ''}}</td>

            <td>
                @if ($product->status == 'active')
                <span class="badge bg-primary">active</span>
                @else
                <span class="badge bg-warning">archived</span>
                @endif
            </td>

            <td>{{$product->created_at}}</td>

            <td>
                <div class="btn-group">
                    @can('update', $product)
                    <a href="{{ route('dashboard.products.edit', $product->id) }}"
                        class="btn btn-block btn-outline-success btn-sm">Edit</a>
                    @endcan

                    @can('delete', $product)
                    <form action="{{route('dashboard.products.destroy', $product->id)}}" method="post">
                        @csrf
                        {{-- <input type="hidden" name="_method" value="delete"> --}}
                        @method('delete')
                        <button type="submit" class="btn btn-block btn-outline-warning btn-sm">
                            Delete
                        </button>
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <th>no products defined!</th>
        </tr>

        @endforelse
    </tbody>
</table>

{{-- this is to get the Pagination style if i have it --}}
{{ $products->withQueryString()->links('pagination::bootstrap-5') }}

@endsection

@section('scripts')
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
@endsection
