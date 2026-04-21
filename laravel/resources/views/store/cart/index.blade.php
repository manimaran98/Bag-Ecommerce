@extends('store.layouts.app')

@section('title', 'Cart — '.config('app.name'))

@section('content')
<div class="container py-4">
    <h1 class="h3 mb-4">Shopping cart</h1>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive shadow-sm rounded-3 overflow-hidden bg-white">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Image</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Line</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $hasStockIssue = false; @endphp
                @forelse ($lines as $i => $line)
                    @php
                        $lineTotal = (int) $line->item_quantity * (float) $line->item_price;
                        $live = $liveQty[(int) $line->item_id] ?? 0;
                        $soldOut = $live <= 0;
                        $insufficient = !$soldOut && $live < (int) $line->item_quantity;
                        if ($soldOut || $insufficient) $hasStockIssue = true;
                    @endphp
                    <tr class="{{ $soldOut || $insufficient ? 'table-warning' : '' }}">
                        <td>{{ $i + 1 }}</td>
                        <td>
                            {{ $line->item_name }}
                            @if ($soldOut)
                                <span class="badge bg-danger ms-1">Sold out</span>
                            @elseif ($insufficient)
                                <span class="badge bg-warning text-dark ms-1">Only {{ $live }} left</span>
                            @endif
                        </td>
                        <td><img class="rounded border" width="120" height="80" style="object-fit:cover" src="{{ asset('assets/stockImg/'.$line->item_img) }}" alt=""></td>
                        <td>{{ $line->item_quantity }}</td>
                        <td>RM {{ $line->item_price }}</td>
                        <td>RM {{ number_format($lineTotal, 2) }}</td>
                        <td>
                            <form method="post" action="{{ route('cart.remove', $line->item_id) }}" class="d-inline" onsubmit="return confirm('Remove this item?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-5">
                            Your cart is empty. <a href="{{ route('products.index') }}">Browse products</a>.
                        </td>
                    </tr>
                @endforelse
                @if (count($lines) > 0)
                    <tr class="table-light fw-semibold">
                        <td colspan="5" class="text-end">Total</td>
                        <td colspan="2">RM {{ number_format($total, 2) }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if (count($lines) > 0)
        <div class="card border-0 bg-light mt-4">
            <div class="card-body d-flex flex-wrap align-items-center justify-content-between gap-3">
                @if ($hasStockIssue)
                    <p class="mb-0 small text-danger fw-semibold">
                        One or more items in your cart are sold out or have reduced stock. Remove or update them before checking out.
                    </p>
                    <button type="button" class="btn btn-success btn-lg" disabled>Pay with card (Stripe)</button>
                @else
                    <p class="mb-0 small text-secondary">Pay with Stripe (test card <code>4242 4242 4242 4242</code>). Set <code>STRIPE_SECRET_KEY</code> in <code>.env</code>.</p>
                    <form method="post" action="{{ route('checkout.stripe') }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">Pay with card (Stripe)</button>
                    </form>
                @endif
            </div>
        </div>
    @endif
</div>
@endsection
