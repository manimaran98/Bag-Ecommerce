@extends('store.layouts.app')

@section('title', 'Invoice — '.config('app.name'))

@section('content')
<div class="container py-4">
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4 p-md-5">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4 pb-3 border-bottom">
                <div>
                    <h1 class="h3 mb-0">{{ config('app.name') }}</h1>
                    <p class="text-secondary mb-0 small">Purchase invoice</p>
                </div>
            </div>

            <dl class="row mb-4">
                <dt class="col-sm-3">Customer</dt>
                <dd class="col-sm-9">{{ $customerName }}</dd>
                <dt class="col-sm-3">Purchase ID</dt>
                <dd class="col-sm-9">{{ $purchase->purchase_id }}</dd>
                <dt class="col-sm-3">Date</dt>
                <dd class="col-sm-9">{{ $purchase->purchase_date ? $purchase->purchase_date->format('d/m/Y') : '—' }}</dd>
            </dl>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Description</th>
                            <th>Qty</th>
                            <th>Unit</th>
                            <th>Line</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grand = 0.0; @endphp
                        @foreach ($purchase->items as $row)
                            @php
                                $qty = (float) $row->stock_quantity;
                                $unit = (float) $row->stock_price;
                                $line = $qty * $unit;
                                $grand += $line;
                            @endphp
                            <tr>
                                <td><img width="100" height="60" class="rounded border" src="{{ asset('assets/stockImg/'.$row->stock_img) }}" alt=""></td>
                                <td>{{ $row->stock_name }}</td>
                                <td>{{ $row->stock_quantity }}</td>
                                <td>RM {{ number_format($unit, 2) }}</td>
                                <td>RM {{ number_format($line, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr class="table-light fw-semibold">
                            <td colspan="4" class="text-end">Total</td>
                            <td>RM {{ number_format($grand, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap gap-2 mt-4">
                <button type="button" class="btn btn-success" onclick="window.print()">Print</button>
                <a class="btn btn-outline-secondary" href="{{ route('orders.index') }}">Back to orders</a>
            </div>
        </div>
    </div>
</div>
@endsection
