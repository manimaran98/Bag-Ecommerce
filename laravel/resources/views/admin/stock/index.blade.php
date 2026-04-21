@extends('admin.layout')

@section('title', 'Stock')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Stock inventory</h1>
    <a href="{{ route('admin.stock.create') }}" class="btn btn-primary btn-sm">Add item</a>
</div>
<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
        <tr><th>ID</th><th>Image</th><th>Name</th><th>Brand</th><th>Qty</th><th>Price</th><th></th></tr>
        </thead>
        <tbody>
        @foreach ($stocks as $s)
            <tr>
                <td>{{ $s->stock_id }}</td>
                <td><img src="{{ asset('assets/stockImg/'.$s->stock_img) }}" alt="" width="56" height="40" class="rounded border"></td>
                <td>{{ $s->stock_name }}</td>
                <td>{{ $s->stock_brand }}</td>
                <td>{{ $s->stock_quantity }}</td>
                <td>RM {{ number_format((float) $s->stock_price, 2) }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.stock.show', $s) }}" class="btn btn-sm btn-outline-secondary">View</a>
                    <a href="{{ route('admin.stock.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="{{ route('admin.stock.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Delete this stock row?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
