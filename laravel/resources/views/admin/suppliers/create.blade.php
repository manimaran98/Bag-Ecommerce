@extends('admin.layout')

@section('title', 'Add supplier')

@section('content')
<h1 class="h3 mb-4">Add supplier</h1>
<form method="post" action="{{ route('admin.suppliers.store') }}" class="card shadow-sm p-4" style="max-width: 480px;">
    @csrf
    <div class="mb-3">
        <label class="form-label">Supplier name</label>
        <input type="text" name="supplierName" value="{{ old('supplierName') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Stock brand</label>
        <input type="text" name="supplierBrand" value="{{ old('supplierBrand') }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.suppliers.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
