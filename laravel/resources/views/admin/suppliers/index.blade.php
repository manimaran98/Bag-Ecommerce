@extends('admin.layout')

@section('title', 'Suppliers')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Suppliers</h1>
    <a href="{{ route('admin.suppliers.create') }}" class="btn btn-primary btn-sm">Add supplier</a>
</div>
<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>ID</th><th>Name</th><th>Brand</th><th></th></tr></thead>
        <tbody>
        @foreach ($suppliers as $s)
            <tr>
                <td>{{ $s->suppliers_id }}</td>
                <td>{{ $s->suppliers_name }}</td>
                <td>{{ $s->stock_brand }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.suppliers.edit', $s) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="{{ route('admin.suppliers.destroy', $s) }}" class="d-inline" onsubmit="return confirm('Delete?');">
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
