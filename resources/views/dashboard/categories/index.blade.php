@extends('dashboard.index')

@section('styles')
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@endsection

@section('title', 'Categories')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Categories</li>
@endsection
@section('content')

<div class="mb-5">
    {{-- using the permissions in the front --}}
    @can('create', 'App\\Models\Category')
    <a href="{{route('dashboard.categories.create')}}" class="btn bg-olive active">Add Category</a>
    @endcan
    {{-- <a href="{{route('categories.trash')}}" class="btn bg-olive active">Trashes Categories</a> --}}
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
            <th>Parent</th>
            <th>Parent Count</th>
            <th>Status</th>
            <th>Created At</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($categories as $category)
        <tr>
            <td>{{$category->id}}</td>
            <td>
                @if ($category->image)
                <img src="{{ asset('storage/'.$category->image) }}" class="rounded-circle" width="50" height="50">
                @else
                <img src="{{ asset('storage/broken-image.png')}}" class="rounded-circle" width="50" height="50">
                @endif
            </td>

            <td><a href="{{route('dashboard.categories.show', $category->id)}}"><span
                        class="badge bg-primary">{{$category->name}}</span></a></td>

            <td>{{$category->parent->name}}</td>
            <td>{{$category->products_number}}</td>

            <td>
                @if ($category->status == 'active')
                <span class="badge bg-primary">active</span>
                @else
                <span class="badge bg-warning">archived</span>
                @endif
            </td>

            <td>{{$category->created_at}}</td>

            <td>
                <div class="btn-group">
                    @can('update', $category)
                    <a href="{{ route('dashboard.categories.edit', $category->id) }}"
                        class="btn btn-block btn-outline-success btn-sm">Edit</a>
                    @endcan

                    @can('delete', $category)
                    <form action="{{route('dashboard.categories.destroy', $category->id)}}" method="post">
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
            <th>no Categories defined!</th>
        </tr>

        @endforelse

        {{-- @if ($categories->count()) --}}
        {{-- @foreach ($categories as $category)
        <tr>
            <td></td>
            <td>{{$category->id}}</td>
            <td>{{$category->name}}</td>
            <td>{{$category->parent_id}}</td>
            <td>{{$category->created_at}}</td>
            <td>
                <a href="{{ route('categories.edit') }}" class="btn btn-block btn-outline-success btn-sm">Edit</a>
            </td>
            <td>
                <form action="{{route('categories.destroy')}}" method="post">
                    @csrf
                    {{-- <input type="hidden" name="_method" value="delete"> --}}
                    {{-- @method('delete')
                    <button type="submit" class="btn btn-block btn-outline-warning btn-sm">
                        Delete
                    </button>
                </form>
            </td>
        </tr> --}}
        {{-- @endforeach --}}
        {{-- @else --}}
        {{-- <tr>
            <td>
                no Categories defined!
            </td>
        </tr> --}}
        {{-- @endif --}}

    </tbody>
</table>

{{-- this is to get the Pagination style if i have it --}}
{{ $categories->withQueryString()->links('pagination::bootstrap-5') }}

@endsection

@section('scripts')
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
@endsection
