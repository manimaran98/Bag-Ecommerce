@extends('admin.layout')

@section('title', 'Admin Dashboard')

@section('content')
<h1 class="h3 mb-4">Sales report</h1>
<form method="get" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-end mb-4">
    <div class="col-auto">
        <label class="form-label small mb-0">From</label>
        <input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm">
    </div>
    <div class="col-auto">
        <label class="form-label small mb-0">To</label>
        <input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm">
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">Clear</a>
    </div>
</form>

<div class="table-responsive card shadow-sm">
    <table class="table table-striped table-hover mb-0 align-middle">
        <thead class="table-light">
        <tr>
            <th>Line ID</th>
            <th>Purchase ID</th>
            <th>User ID</th>
            <th>Stock ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Qty</th>
            <th>Date</th>
            <th>Unit</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @forelse ($items as $row)
            <tr>
                <td>{{ $row->purchase_item_id }}</td>
                <td>{{ $row->purchase_id }}</td>
                <td>{{ $row->id }}</td>
                <td>{{ $row->stock_id }}</td>
                <td><img src="{{ asset('assets/stockImg/'.$row->stock_img) }}" alt="" width="80" height="48" class="rounded border"></td>
                <td>{{ $row->stock_name }}</td>
                <td>{{ $row->stock_quantity }}</td>
                <td>{{ $row->purchase_date ? $row->purchase_date->format('d/m/Y') : '—' }}</td>
                <td>RM {{ number_format((float) $row->stock_price, 2) }}</td>
                <td class="text-nowrap">
                    <a href="{{ route('admin.purchase-items.edit', $row) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form method="post" action="{{ route('admin.purchase-items.destroy', $row) }}" class="d-inline" onsubmit="return confirm('Delete this line?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center text-secondary py-5">No purchase lines yet.</td>
            </tr>
        @endforelse
        </tbody>
        <tfoot class="table-light">
        <tr>
            <td colspan="6" class="text-end fw-semibold">Totals</td>
            <td class="fw-semibold">{{ $totalQty }}</td>
            <td colspan="1"></td>
            <td class="fw-semibold">RM {{ number_format($totalAmount, 2) }}</td>
            <td><button type="button" class="btn btn-success btn-sm" onclick="window.print()">Print</button></td>
        </tr>
        </tfoot>
    </table>
</div>
@if ($items->hasPages())
    <div class="mt-3">{{ $items->links() }}</div>
@endif
@endsection
