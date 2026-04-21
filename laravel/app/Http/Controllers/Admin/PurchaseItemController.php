<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PurchaseItemController extends Controller
{
    public function edit(PurchaseItem $purchase_item): View
    {
        return view('admin.purchase-items.edit', ['item' => $purchase_item]);
    }

    public function update(Request $request, PurchaseItem $purchase_item): RedirectResponse
    {
        $data = $request->validate([
            'purchase_id' => ['required', 'string', 'max:100'],
            'id' => ['required', 'integer', 'exists:users,id'],
            'stock_id' => ['required', 'integer'],
            'stock_name' => ['required', 'string', 'max:100'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'stock_price' => ['required', 'string', 'max:100'],
            'purchase_date' => ['required', 'date'],
        ]);

        $purchase_item->fill($data);
        $purchase_item->save();

        return redirect()->route('admin.dashboard')->with('status', 'Purchase line updated.');
    }

    public function destroy(PurchaseItem $purchase_item): RedirectResponse
    {
        $purchase_item->delete();

        return redirect()->route('admin.dashboard')->with('status', 'Purchase line deleted.');
    }
}
