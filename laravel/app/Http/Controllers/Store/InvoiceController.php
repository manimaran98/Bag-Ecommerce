<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function show(Request $request, string $purchase): View
    {
        $model = Purchase::query()->with('items')->findOrFail($purchase);
        $this->authorize('view', $model);

        return view('store.invoice.show', [
            'purchase' => $model,
            'customerName' => $request->user()->name,
        ]);
    }
}
