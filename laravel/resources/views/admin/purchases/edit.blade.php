@extends('admin.layout')

@section('title', 'Edit purchase')

@section('content')
<h1 class="h3 mb-4">Purchase {{ $purchase->purchase_id }}</h1>

@if ($purchase->stripe_checkout_session_id)
    <p class="text-secondary small">Paid with Stripe (session {{ $purchase->stripe_checkout_session_id }}). Receipt file is optional.</p>
@endif

<form method="post" action="{{ route('admin.purchases.update', $purchase) }}" enctype="multipart/form-data" class="card shadow-sm p-4 mb-4" style="max-width: 560px;">
    @csrf
    @method('PUT')
    <div class="mb-3">
        <label class="form-label">User ID</label>
        <input type="number" name="id" value="{{ old('id', $purchase->id) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Total price</label>
        <input type="text" name="total_price" value="{{ old('total_price', $purchase->total_price) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Purchase date</label>
        <input type="date" name="purchase_date" value="{{ old('purchase_date', $purchase->purchase_date?->format('Y-m-d')) }}" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Validation</label>
        <select name="purchase_validation" class="form-select" required>
            @foreach (['Processing', 'Approved', 'Declined'] as $v)
                <option value="{{ $v }}" @selected(old('purchase_validation', $purchase->purchase_validation) === $v)>{{ $v }}</option>
            @endforeach
        </select>
        <div class="form-text">Setting to Declined restores stock and removes purchase line items (legacy behavior).</div>
    </div>
    <div class="mb-3">
        <label class="form-label">Replace receipt (optional)</label>
        <input type="file" name="payment_resit" class="form-control" accept=".jpg,.jpeg,.png,.gif,.webp,.pdf">
        @if ($purchase->payment_resit)
            <div class="mt-2">
                <a href="{{ route('receipt.download', ['payment_resit' => $purchase->payment_resit]) }}" class="btn btn-sm btn-outline-secondary">Download current</a>
            </div>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
    <a href="{{ route('admin.purchases.index') }}" class="btn btn-outline-secondary">Back</a>
</form>

<h2 class="h5">Line items</h2>
<div class="table-responsive card shadow-sm">
    <table class="table table-sm mb-0">
        <thead class="table-light"><tr><th>ID</th><th>Stock</th><th>Qty</th><th>Price</th></tr></thead>
        <tbody>
        @forelse ($purchase->items as $it)
            <tr>
                <td>{{ $it->purchase_item_id }}</td>
                <td>{{ $it->stock_name }}</td>
                <td>{{ $it->stock_quantity }}</td>
                <td>{{ $it->stock_price }}</td>
            </tr>
        @empty
            <tr><td colspan="4" class="text-secondary">No lines (e.g. after decline).</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
