@extends('admin.layout')

@section('title', 'Add stock')

@section('content')
<h1 class="h3 mb-4">Add item</h1>
<form method="post" action="{{ route('admin.stock.store') }}" enctype="multipart/form-data" class="card shadow-sm p-4" style="max-width: 560px;">
    @csrf
    <div class="mb-3">
        <label class="form-label">Name</label>
        <input type="text" name="itemName" value="{{ old('itemName') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Brand</label>
        <input type="text" name="itemBrand" value="{{ old('itemBrand') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Category</label>
        <input type="text" name="itemCategory" value="{{ old('itemCategory') }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Price (RM, integer)</label>
        <input type="number" name="itemPrice" value="{{ old('itemPrice') }}" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Quantity</label>
        <input type="number" name="itemQuantity" value="{{ old('itemQuantity') }}" class="form-control" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="itemDescription" class="form-control" rows="4" required>{{ old('itemDescription') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Image</label>
        <input type="file" name="itemImg" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.stock.index') }}" class="btn btn-outline-secondary">Cancel</a>
</form>
@endsection
