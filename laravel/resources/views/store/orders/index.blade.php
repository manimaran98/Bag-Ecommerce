@extends('store.layouts.app')

@section('title', 'Orders — '.config('app.name'))

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Track your orders</h1>

    @if (request('paid'))
        <div class="alert alert-success">Payment received. Your order is recorded.</div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="get" action="{{ route('orders.index') }}" class="row g-2 align-items-end">
                <div class="col-md-4">
                    <label class="form-label" for="delivery_id">Filter by delivery ID</label>
                    <input type="text" class="form-control" name="delivery_id" id="delivery_id" value="{{ request('delivery_id') }}" placeholder="Leave empty for all">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive shadow-sm rounded-3 bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Delivery ID</th>
                    <th>Purchase ID</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Address</th>
                    <th>Payment</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deliveries as $d)
                    <tr>
                        <td>{{ $d->delivery_id }}</td>
                        <td>{{ $d->purchase_id }}</td>
                        <td>{{ $d->delivery_agent }}</td>
                        <td>{{ $d->delivery_status }}</td>
                        <td>{{ $d->address }}</td>
                        <td>{{ $d->payment_status }}</td>
                        <td><a class="btn btn-sm btn-outline-primary" href="{{ route('invoice.show', $d->purchase_id) }}">Invoice</a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-5">No orders yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
