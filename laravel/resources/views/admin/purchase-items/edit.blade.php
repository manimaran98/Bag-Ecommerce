@extends('admin.layout')

@section('title', 'Edit purchase line')

@section('content')
<h1 class="h3 mb-4">Purchase line #{{ $item->purchase_item_id }}</h1>
<form method="post" action="{{ route('admin.purchase-items.update', $item) }}" class="card shadow-sm p-4" style="max-width: 560px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Purchase ID</label>
        <input type="text" name="purchase_id" value="{{ old('purchase_id', $item->purchase_id) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">User ID</label>
        <input type="number" name="id" value="{{ old('id', $item->id) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Stock ID</label>
        <input type="number" name="stock_id" value="{{ old('stock_id', $item->stock_id) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Stock name</label>
        <input type="text" name="stock_name" value="{{ old('stock_name', $item->stock_name) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $item->stock_quantity) }}" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="text" name="stock_price" value="{{ old('stock_price', $item->stock_price) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Purchase date</label>
        <input type="date" name="purchase_date" value="{{ old('purchase_date', $item->purchase_date?->format('Y-m-d')) }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back</a>
</form>
@endsection
