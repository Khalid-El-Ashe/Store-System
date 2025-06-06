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
    <a href="{{route('categories.create')}}" class="btn bg-olive active">Add Category</a>
</div>

{{-- @component('components.alert') --}}
<x-alert type="success" />
<!--- this component i added it --->

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Parent</th>
            <th>Created At</th>
            <th>Action</th>
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
            <td>{{$category->name}}</td>
            <td>{{$category->parent_id}}</td>
            <td>{{$category->created_at}}</td>
            <td>
                <div class="btn-group">
                    <a href="{{ route('categories.edit', $category->id) }}"
                        class="btn btn-block btn-outline-success btn-sm">Edit</a>
                    <form action="{{route('categories.destroy', $category->id)}}" method="post">
                        @csrf
                        {{-- <input type="hidden" name="_method" value="delete"> --}}
                        @method('delete')
                        <button type="submit" class="btn btn-block btn-outline-warning btn-sm">
                            Delete
                        </button>
                    </form>
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

@endsection

@section('scripts')
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
@endsection
