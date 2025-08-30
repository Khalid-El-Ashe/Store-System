@extends('layouts.dashboard')

@section('title', 'Users')

@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Users</li>
@endsection

@section('content')

<div class="mb-5">
    @can('create', 'App\\Models\User')
    <a href="{{ route('dashboard.users.create') }}" class="btn bg-olive active">Create User</a>
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
        @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td><a href="{{ route('dashboard.users.show', $user->id) }}">{{ $user->name }}</a></td>
            <td>{{ $user->email }}</td>
            <td></td>
            <td>{{ $user->created_at }}</td>
            <td>
                <div class="btn-group">
                    @can('update', $user)
                    <a href="{{ route('dashboard.users.edit', $user->id) }}"
                        class="btn btn-sm btn-outline-success">Edit</a>
                    @endcan

                    @can('delete', $user)
                    <form action="{{ route('dashboard.users.destroy', $user->id) }}" method="post">
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
            <td colspan="6">No users defined.</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $users->withQueryString()->links() }}

@endsection
