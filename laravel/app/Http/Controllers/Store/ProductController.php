<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StockInventory;
use App\Models\UserSearchLog;
use App\Services\ProductRecommendationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRecommendationService $recommendations
    ) {}

    public function index(Request $request): View
    {
        $search = $request->query('search');
        $q = StockInventory::query();
        if (is_string($search) && $search !== '' && $search !== 'AllCategory') {
            $q->where('stock_category', $search);
        }

        $this->logCategoryFilter($request, $search);

        $recommended = collect();
        if (Auth::check()) {
            $recommended = $this->recommendations->recommendForUser(Auth::user(), (int) config('halenmiaga.recommendation_limit', 8));
        }

        return view('store.shop.index', [
            'products' => $q->orderBy('stock_id')->get(),
            'search' => is_string($search) ? $search : null,
            'recommended' => $recommended,
        ]);
    }

    public function show(StockInventory $stock): View
    {
        $recommended = collect();
        if (Auth::check()) {
            $recommended = $this->recommendations->recommendForUser(Auth::user(), (int) config('halenmiaga.recommendation_limit', 8))
                ->filter(fn (StockInventory $p) => $p->stock_id !== $stock->stock_id)
                ->values();
        }

        return view('store.shop.show', [
            'product' => $stock,
            'recommended' => $recommended,
        ]);
    }

    private function logCategoryFilter(Request $request, mixed $search): void
    {
        if (! Auth::check() || ! is_string($search) || $search === '' || $search === 'AllCategory') {
            return;
        }

        UserSearchLog::query()->create([
            'user_id' => Auth::id(),
            'category_filter' => $search,
            'created_at' => now(),
        ]);
    }
}
