@extends('dashboard.index')

@section('styles')
<link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
@endsection

@section('title', 'Roles')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Roles</li>
@endsection
@section('content')

<div class="mb-5">
    {{-- using the permissions in the front --}}
    @can('roles.create')
    <a href="{{route('dashboard.roles.create')}}" class="btn bg-olive active">Add Role</a>
    @endcan
</div>

{{-- @component('components.alert') --}}
<x-alert type="success" />
<!--- this component i added it --->

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Created At</th>
            <th colspan="2">Action</th>
        </tr>
    </thead>
    <tbody>

        @forelse ($roles as $role)
        <tr>
            <td>{{$role->id}}</td>

            <td><a href="{{route('dashboard.roles.edit', $role->id)}}"><span
                        class="badge bg-primary">{{$role->name}}</span></a>
            </td>

            <td>{{$role->created_at}}</td>

            <td>
                <div class="btn-group">
                    @can('roles.update')
                    <a href="{{ route('dashboard.roles.edit', $role->id) }}"
                        class="btn btn-block btn-outline-success btn-sm">Edit</a>
                    @endcan

                    @can('roles.delete')
                    <form action="{{route('dashboard.roles.destroy', $role->id)}}" method="post">
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
            <th colspan="4">no roles defined!</th>
        </tr>

        @endforelse

        {{-- @if ($roles->count()) --}}
        {{-- @foreach ($roles as $role)
        <tr>
            <td></td>
            <td>{{$role->id}}</td>
            <td>{{$role->name}}</td>
            <td>{{$role->parent_id}}</td>
            <td>{{$role->created_at}}</td>
            <td>
                <a href="{{ route('roles.edit') }}" class="btn btn-block btn-outline-success btn-sm">Edit</a>
            </td>
            <td>
                <form action="{{route('roles.destroy')}}" method="post">
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
                no roles defined!
            </td>
        </tr> --}}
        {{-- @endif --}}

    </tbody>
</table>

{{-- this is to get the Pagination style if i have it --}}
{{ $roles->withQueryString()->links() }}

@endsection

@section('scripts')
<script src="{{asset('plugins/toastr/toastr.min.js')}}"></script>
@endsection