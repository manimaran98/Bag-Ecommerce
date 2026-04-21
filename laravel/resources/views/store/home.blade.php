@extends('store.layouts.app')

@section('title', config('app.name'))

@section('content')
<div class="alert alert-info hm-hero-alert rounded-0 mb-0 text-center py-3 shadow-sm">
    Shop from home — browse bags and pay securely with Stripe (test mode).
</div>
<div class="container py-5">
    <h1 class="h3 mb-4 text-center">Welcome to {{ config('app.name') }}</h1>
    <p class="text-center text-secondary col-lg-8 mx-auto mb-4">
        Use the navigation to browse products, manage your cart, and track orders. This storefront runs on Laravel with session authentication and CSRF protection.
    </p>
    @guest
        <div class="text-center">
            <a class="btn btn-primary btn-lg me-2" href="{{ route('login') }}">Login</a>
            <a class="btn btn-outline-primary btn-lg" href="{{ route('register') }}">Register</a>
        </div>
    @else
        <div class="text-center">
            <a class="btn btn-primary btn-lg me-2" href="{{ route('products.index') }}">Browse products</a>
            <a class="btn btn-outline-primary btn-lg" href="{{ route('cart.index') }}">Your cart</a>
        </div>
        @include('store.partials.recommended-products', ['recommended' => $recommended ?? collect()])
    @endguest
</div>
@endsection
