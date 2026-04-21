@extends('admin.layout')

@section('title', 'Edit order')

@section('content')
<h1 class="h3 mb-4">Edit delivery #{{ $delivery->delivery_id }}</h1>
<form method="post" action="{{ route('admin.deliveries.update', $delivery) }}" class="card shadow-sm p-4" style="max-width: 560px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">User ID</label>
        <input type="number" name="id" value="{{ old('id', $delivery->id) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Purchase ID</label>
        <input type="text" name="purchase_id" value="{{ old('purchase_id', $delivery->purchase_id) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Delivery agent</label>
        <input type="text" name="delivery_agent" value="{{ old('delivery_agent', $delivery->delivery_agent) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Delivery status</label>
        <input type="text" name="delivery_status" value="{{ old('delivery_status', $delivery->delivery_status) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Address</label>
        <input type="text" name="address" value="{{ old('address', $delivery->address) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Payment status</label>
        <input type="text" name="payment_status" value="{{ old('payment_status', $delivery->payment_status) }}" class="form-control" required>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.deliveries.index') }}" class="btn btn-outline-secondary">Back</a>
</form>
@endsection
