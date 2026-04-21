<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — '.config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row g-0">
        <nav class="col-md-3 col-lg-2 bg-dark text-white p-3 min-vh-100 d-flex flex-column" aria-label="Admin">
            <div class="fw-semibold mb-3 pb-2 border-bottom border-secondary">Admin</div>
            <nav class="nav nav-pills flex-column gap-1 mb-auto">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-white-50' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : 'text-white-50' }}" href="{{ route('admin.users.index') }}">Users</a>
                <a class="nav-link {{ request()->routeIs('admin.stock.*') ? 'active' : 'text-white-50' }}" href="{{ route('admin.stock.index') }}">Stock</a>
                <a class="nav-link {{ request()->routeIs('admin.suppliers.*') ? 'active' : 'text-white-50' }}" href="{{ route('admin.suppliers.index') }}">Suppliers</a>
                <a class="nav-link {{ request()->routeIs('admin.deliveries.*') ? 'active' : 'text-white-50' }}" href="{{ route('admin.deliveries.index') }}">Orders</a>
                <a class="nav-link {{ request()->routeIs('admin.purchases.*') ? 'active' : 'text-white-50' }}" href="{{ route('admin.purchases.index') }}">Purchases</a>
                <a class="nav-link {{ request()->routeIs('admin.help-desk.*') ? 'active' : 'text-white-50' }}" href="{{ route('admin.help-desk.index') }}">Help desk</a>
            </nav>
            <hr class="border-secondary">
            <a class="link-light link-underline-opacity-0 mb-2" href="{{ route('home') }}"><i class="bi bi-shop me-1" aria-hidden="true"></i>Storefront</a>
            <form method="post" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">Logout</button>
            </form>
        </nav>
        <main class="col-md-9 col-lg-10 p-4">
            @if (session('status'))
                <div class="alert alert-success shadow-sm">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger shadow-sm">
                    <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
@stack('scripts')
</body>
</html>
