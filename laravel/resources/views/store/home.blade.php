@extends('store.layouts.app')

@section('title', config('app.name'))

@section('content')
<div class="alert alert-info border-0 rounded-0 mb-0 text-center py-3 shadow-sm">
    <i class="bi bi-shield-check me-1" aria-hidden="true"></i>
    Shop from home — secure payment with Stripe (use test cards in development).
</div>

<section class="bg-primary text-white text-center py-5">
    <div class="container py-2">
        <p class="small text-uppercase text-white-50 mb-2">Curated bags</p>
        <h1 class="display-5 fw-bold mb-3">Find your next everyday carry</h1>
        <p class="mx-auto mb-0 col-lg-8 lead">
            Browse the catalog, build your cart, and check out in a few clicks. Signed-in customers get personalized picks based on what you buy and explore.
        </p>
        <div class="d-flex flex-wrap gap-2 justify-content-center mt-4">
            <span class="badge text-bg-light text-primary rounded-pill">Stripe Checkout</span>
            <span class="badge text-bg-light text-primary rounded-pill">Order history</span>
            <span class="badge text-bg-light text-primary rounded-pill">Recommendations</span>
        </div>
    </div>
</section>

<div class="container py-5">
    @guest
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <p class="text-secondary mb-4">Create an account to save your cart, pay securely, and track deliveries.</p>
                <a class="btn btn-primary btn-lg px-4 me-2 mb-2" href="{{ route('login') }}">Sign in</a>
                <a class="btn btn-outline-primary btn-lg px-4 mb-2" href="{{ route('register') }}">Create account</a>
            </div>
        </div>
    @else
        <div class="row justify-content-center mb-4">
            <div class="col-lg-8 text-center">
                <p class="text-secondary mb-4">Welcome back. Jump into the catalog or open your cart.</p>
                <a class="btn btn-primary btn-lg px-4 me-2 mb-2" href="{{ route('products.index') }}">
                    <i class="bi bi-grid-3x3-gap me-1" aria-hidden="true"></i>Browse products
                </a>
                <a class="btn btn-outline-primary btn-lg px-4 mb-2" href="{{ route('cart.index') }}">
                    <i class="bi bi-bag me-1" aria-hidden="true"></i>Your cart
                </a>
            </div>
        </div>
        @include('store.partials.recommended-products', ['recommended' => $recommended ?? collect()])
    @endguest
</div>
@endsection
