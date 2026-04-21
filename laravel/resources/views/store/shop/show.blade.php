@extends('store.layouts.app')

@section('title', $product->stock_name.' — '.config('app.name'))

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="row g-0">
                    <div class="col-md-6 bg-light">
                        <img src="{{ asset('assets/stockImg/'.$product->stock_img) }}" class="w-100 h-100 object-fit-cover" style="min-height: 280px;" alt="">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4">
                            <h1 class="h3 mb-3">{{ $product->stock_name }}</h1>
                            <p class="text-secondary mb-2"><strong>Brand:</strong> {{ $product->stock_brand }}</p>
                            <p class="text-secondary mb-2"><strong>Category:</strong> {{ $product->stock_category }}</p>
                            <p class="mb-2"><strong>Price:</strong> RM {{ $product->stock_price }}</p>
                            <p class="mb-3"><strong>Stock:</strong> {{ $product->stock_quantity <= 0 ? 'Out of stock' : $product->stock_quantity }}</p>
                            <p class="small text-muted mb-4">{{ $product->stock_description }}</p>

                            @if ($product->stock_quantity > 0)
                                @auth
                                    <form method="post" action="{{ route('cart.add', $product->stock_id) }}">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="quantity">Quantity</label>
                                            <input class="form-control" type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock_quantity }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">Add to cart</button>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-danger w-100">Login to purchase</a>
                                    <p class="small text-muted text-center mt-2">
                                        Don't have an account? <a href="{{ route('register') }}">Sign up</a>
                                    </p>
                                @endauth
                            @else
                                <p class="text-danger fw-semibold">Currently unavailable.</p>
                            @endif
                            <a class="btn btn-outline-secondary w-100 mt-2" href="{{ route('products.index') }}">Back to products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('store.partials.recommended-products', [
        'recommended' => $recommended ?? collect(),
        'title' => 'You may also like',
    ])
</div>
@endsection
