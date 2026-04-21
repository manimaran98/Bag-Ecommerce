<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin — '.config('app.name'))</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('assets/css/halenmiaga.css') }}?v=2">
    <style>
        .hm-admin-sidebar { min-height: 100vh; background: #1e293b; }
        .hm-admin-sidebar a { color: #e2e8f0; text-decoration: none; display: block; padding: .5rem .75rem; border-radius: .35rem; }
        .hm-admin-sidebar a:hover, .hm-admin-sidebar a.active { background: #334155; color: #fff; }
    </style>
    @stack('styles')
</head>
<body class="bg-light">
<div class="container-fluid">
    <div class="row g-0">
        <nav class="col-md-3 col-lg-2 hm-admin-sidebar p-3">
            <div class="text-white fw-semibold mb-3 pb-2 border-bottom border-secondary">Admin</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Users</a>
            <a href="{{ route('admin.stock.index') }}" class="{{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">Stock</a>
            <a href="{{ route('admin.suppliers.index') }}" class="{{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">Suppliers</a>
            <a href="{{ route('admin.deliveries.index') }}" class="{{ request()->routeIs('admin.deliveries.*') ? 'active' : '' }}">Orders</a>
            <a href="{{ route('admin.purchases.index') }}" class="{{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">Purchases</a>
            <a href="{{ route('admin.help-desk.index') }}" class="{{ request()->routeIs('admin.help-desk.*') ? 'active' : '' }}">Help desk</a>
            <hr class="border-secondary">
            <a href="{{ route('home') }}">Storefront</a>
            <form method="post" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="btn btn-outline-light btn-sm w-100">Logout</button>
            </form>
        </nav>
        <main class="col-md-9 col-lg-10 p-4">
            @if (session('status'))
                <div class="alert alert-success">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
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
