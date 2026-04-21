<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $from = $request->query('from');
        $to = $request->query('to');

        $query = PurchaseItem::query()->orderByDesc('purchase_date')->orderByDesc('purchase_item_id');

        if ($from !== null && $from !== '' && $to !== null && $to !== '') {
            $query->whereBetween('purchase_date', [$from, $to]);
        }

        $rows = $query->get();

        $totalQty = 0;
        $totalAmount = 0.0;
        foreach ($rows as $row) {
            $qty = (int) $row->stock_quantity;
            $unit = (float) $row->stock_price;
            $totalQty += $qty;
            $totalAmount += $qty * $unit;
        }

        return view('admin.dashboard', [
            'items' => $rows,
            'totalQty' => $totalQty,
            'totalAmount' => $totalAmount,
            'from' => $from,
            'to' => $to,
        ]);
    }
}
