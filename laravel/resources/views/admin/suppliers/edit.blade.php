@extends('admin.layout')

@section('title', 'Edit supplier')

@section('content')
<h1 class="h3 mb-4">Edit supplier #{{ $supplier->suppliers_id }}</h1>
<form method="post" action="{{ route('admin.suppliers.update', $supplier) }}" class="card shadow-sm p-4" style="max-width: 480px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Supplier name</label>
        <input type="text" name="suppliers_name" value="{{ old('suppliers_name', $supplier->suppliers_name) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Stock brand</label>
        <input type="text" name="stock_brand" value="{{ old('stock_brand', $supplier->stock_brand) }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Back</a>
</form>
@endsection
