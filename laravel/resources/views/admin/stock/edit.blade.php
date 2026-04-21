@extends('admin.layout')

@section('title', 'Edit stock')

@section('content')
<h1 class="h3 mb-4">Edit stock #{{ $stock->stock_id }}</h1>
<form method="post" action="{{ route('admin.stock.update', $stock) }}" enctype="multipart/form-data" class="card shadow-sm p-4" style="max-width: 560px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="stock_name" value="{{ old('stock_name', $stock->stock_name) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Brand</label>
        <input type="text" name="stock_brand" value="{{ old('stock_brand', $stock->stock_brand) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="stock_category" value="{{ old('stock_category', $stock->stock_category) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Price (integer)</label>
        <input type="number" name="stock_price" value="{{ old('stock_price', $stock->stock_price) }}" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $stock->stock_quantity) }}" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="stock_description" class="form-control" rows="4" required>{{ old('stock_description', $stock->stock_description) }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Replace image (optional)</label>
        <input type="file" name="stock_img" class="form-control" accept="image/*">
        <div class="form-text">Leave empty to keep current image.</div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
