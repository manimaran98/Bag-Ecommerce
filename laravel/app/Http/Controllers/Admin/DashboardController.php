<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $hasRange = $from !== null && $from !== '' && $to !== null && $to !== '';

        $query = PurchaseItem::query()->orderByDesc('purchase_date')->orderByDesc('purchase_item_id');

        if ($hasRange) {
            $query->whereBetween('purchase_date', [$from, $to]);
        }

        $items = $query->paginate(50)->withQueryString();

        $cacheKey = 'dashboard:stats:'.($hasRange ? "{$from}:{$to}" : 'all');
        [$totalQty, $totalAmount] = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($hasRange, $from, $to) {
            $agg = DB::table('purchase_item')
                ->when($hasRange, fn ($q) => $q->whereBetween('purchase_date', [$from, $to]))
                ->selectRaw('COALESCE(SUM(stock_quantity), 0) as total_qty, COALESCE(SUM(stock_quantity * stock_price), 0) as total_amount')
                ->first();

            return [(int) $agg->total_qty, (float) $agg->total_amount];
        });

        return view('admin.dashboard', [
            'items' => $items,
            'totalQty' => $totalQty,
            'totalAmount' => $totalAmount,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
