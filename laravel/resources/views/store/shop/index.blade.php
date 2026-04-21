@extends('store.layouts.app')

@section('title', 'Products — '.config('app.name'))

@section('content')
<div class="container py-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <div>
            <h1 class="h3 mb-1">Products</h1>
            <p class="small text-secondary mb-0">Filter by category — prices in RM.</p>
        </div>
        <form method="get" action="{{ route('products.index') }}" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0 small text-secondary" for="search">Category</label>
            <select class="form-select form-select-sm rounded-pill border-secondary-subtle" style="min-width: 11rem;" name="search" id="search" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="AllCategory" @selected($search === 'AllCategory')>All categories</option>
                <option value="Mens" @selected($search === 'Mens')>Mens</option>
                <option value="Women" @selected($search === 'Women')>Women</option>
                <option value="Sports" @selected($search === 'Sports')>Sports</option>
            </select>
        </form>
    </div>
    @if (session('status'))
        <div class="alert alert-info">{{ session('status') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif
    <div class="row g-4">
        @forelse ($products as $p)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="{{ asset('assets/stockImg/'.$p->stock_img) }}" class="card-img-top object-fit-cover" alt="" style="height: 220px;">
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title">{{ $p->stock_name }}</h2>
                        <p class="small text-secondary mb-2">RM {{ $p->stock_price }} · {{ $p->stock_category }}</p>
                        <p class="small text-muted flex-grow-1">{{ \Illuminate\Support\Str::limit($p->stock_description, 120) }}</p>
                        <a class="btn btn-primary mt-2" href="{{ route('products.show', $p) }}">View details</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-secondary">No products found.</p>
        @endforelse
    </div>

    @if ($products->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif

    @include('store.partials.recommended-products', ['recommended' => $recommended ?? collect()])
</div>
@endsection
