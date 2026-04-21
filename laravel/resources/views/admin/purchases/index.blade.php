@extends('admin.layout')

@section('title', 'Purchases')

@section('content')
<h1 class="h3 mb-4">Purchases</h1>
<div class="table-responsive card shadow-sm">
    <table class="table table-hover mb-0 small">
        <thead class="table-light">
        <tr>
            <th>Purchase ID</th>
            <th>User</th>
            <th>Total</th>
            <th>Date</th>
            <th>Validation</th>
            <th>Stripe</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach ($purchases as $p)
            <tr>
                <td>{{ $p->purchase_id }}</td>
                <td>{{ $p->user?->username ?? $p->id }}</td>
                <td>RM {{ $p->total_price }}</td>
                <td>{{ $p->purchase_date ? $p->purchase_date->format('d/m/Y') : '—' }}</td>
                <td>{{ $p->purchase_validation }}</td>
                <td>{{ $p->stripe_checkout_session_id ? 'yes' : '—' }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.purchases.edit', $p) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="{{ route('admin.purchases.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Delete purchase and related rows?');">
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
