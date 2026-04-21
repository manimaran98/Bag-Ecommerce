@extends('admin.layout')

@section('title', 'Orders')

@section('content')
<h1 class="h3 mb-4">Customer orders (delivery)</h1>
<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Purchase ID</th>
            <th>Agent</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Address</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($deliveries as $d)
            <tr>
                <td>{{ $d->delivery_id }}</td>
                <td>{{ $d->user?->username ?? $d->id }}</td>
                <td>{{ $d->purchase_id }}</td>
                <td>{{ $d->delivery_agent }}</td>
                <td>{{ $d->delivery_status }}</td>
                <td>{{ $d->payment_status }}</td>
                <td>{{ $d->address }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.deliveries.edit', $d) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="{{ route('admin.deliveries.destroy', $d) }}" class="d-inline" onsubmit="return confirm('Delete this row?');">
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
