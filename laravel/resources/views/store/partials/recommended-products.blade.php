@props([
    'recommended',
    'title' => 'Recommended for you',
])

@if ($recommended instanceof \Illuminate\Support\Collection && $recommended->isNotEmpty())
    <section class="mt-5 pt-4 border-top" aria-labelledby="rec-heading">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <h2 id="rec-heading" class="h5 mb-0">{{ $title }}</h2>
            <span class="badge rounded-pill">For you</span>
        </div>
        <p class="small text-muted mb-3">Based on items bought together, categories you browse, then popular picks.</p>
        <div class="row g-3">
            @foreach ($recommended as $p)
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="card h-100 border-0 shadow-sm position-relative">
                        <a href="{{ route('products.show', $p) }}" class="text-decoration-none text-dark stretched-link">
                            <img src="{{ asset('assets/stockImg/'.$p->stock_img) }}" class="card-img-top object-fit-cover" alt="" style="height: 140px;">
                            <div class="card-body p-2">
                                <div class="small fw-semibold text-truncate">{{ $p->stock_name }}</div>
                                <div class="small text-secondary">RM {{ $p->stock_price }}</div>
                            </div>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
@endif
