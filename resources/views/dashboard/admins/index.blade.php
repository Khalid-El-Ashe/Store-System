@extends('layouts.dashboard')

@section('title', 'Admins')

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Admins</li>
@endsection

@section('content')

<div class="mb-5">
    @can('create', 'App\\Models\Admin')
    <a href="{{ route('dashboard.admins.create') }}" class="btn bg-olive active">Create Admin</a>
    @endcan
</div>

<x-alert type="success" />
<x-alert type="info" />

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Roles</th>
            <th>Created At</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
        @forelse($admins as $admin)
        <tr>
            <td>{{ $admin->id }}</td>
            <td><a href="{{ route('dashboard.admins.edit', $admin->id) }}">{{ $admin->name }}</a></td>
            <td>{{ $admin->email }}</td>
            <td></td>
            <td>{{ $admin->created_at }}</td>
            <td>
                <div class="btn-group">
                    @can('admins.update')
                    <a href="{{ route('dashboard.admins.edit', $admin->id) }}"
                        class="btn btn-sm btn-outline-success">Edit</a>
                    @endcan
                    @can('admins.delete')
                    <form action="{{ route('dashboard.admins.destroy', $admin->id) }}" method="post">
                        @csrf
                        <!-- Form Method Spoofing -->
                        <input type="hidden" name="_method" value="delete">
                        @method('delete')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                    @endcan
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6">No admins defined.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $admins->withQueryString()->links() }}

@endsection
