@extends('dashboard.index')
@section('title', '{{$category->name}}')
@section('breadcrumb')
@parent
<li class="breadcrumb-item active">Categories</li>
<li class="breadcrumb-item active">{{$category->name}}</li>
@endsection
@section('content')

<table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Store</th>
            <th>Status</th>
            <th>Created At</th>
        </tr>
    </thead>
    <tbody>

        @php
        $products = $category->products()->with('store')->latest()->paginate(5);
        @endphp
        @forelse ($products as $product)
        <tr>
            <td>{{$product->name}}</td>

            <td>{{$product->store->name}}</td>

            <td>
                @if ($product->status == 'active')
                <span class="badge bg-primary">active</span>
                @else
                <span class="badge bg-warning">archived</span>
                @endif
            </td>

            <td>{{$product->created_at}}</td>
        </tr>
        @empty
        <tr>
            <th>no products defined!</th>
        </tr>

        @endforelse
    </tbody>
</table>
{{$products->links()}}
@endsection
