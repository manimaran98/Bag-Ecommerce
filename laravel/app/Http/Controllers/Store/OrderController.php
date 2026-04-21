<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $userId = (int) $request->user()->id;
        $q = Delivery::query()->where('id', $userId)->orderByDesc('delivery_id');

        if ($request->filled('delivery_id')) {
            $q->where('delivery_id', $request->input('delivery_id'));
        }

        return view('store.orders.index', [
            'deliveries' => $q->get(),
        ]);
    }
}
