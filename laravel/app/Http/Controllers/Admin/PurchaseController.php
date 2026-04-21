<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use App\Services\PurchaseAdminService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class PurchaseController extends Controller
{
    public function __construct(
        private readonly PurchaseAdminService $purchaseAdmin
    ) {}

    public function index(): View
    {
        $purchases = Purchase::query()->with('user')->orderByDesc('purchase_date')->paginate(25);

        return view('admin.purchases.index', compact('purchases'));
    }

    public function edit(Purchase $purchase): View
    {
        $purchase->load('items');

        return view('admin.purchases.edit', ['purchase' => $purchase]);
    }

    public function update(Request $request, Purchase $purchase): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'total_price' => ['required', 'string', 'max:100'],
            'purchase_date' => ['required', 'date'],
            'purchase_validation' => ['required', 'string', 'max:100'],
            'payment_resit' => ['nullable', 'file', 'max:20480', 'mimes:jpg,jpeg,png,gif,webp,pdf'],
        ]);

        $receipt = $request->file('payment_resit');

        $this->purchaseAdmin->updatePurchase($purchase, [
            'id' => $data['id'],
            'total_price' => $data['total_price'],
            'purchase_date' => $data['purchase_date'],
            'purchase_validation' => $data['purchase_validation'],
        ], $receipt);

        return redirect()->route('admin.purchases.index')->with('status', 'Purchase updated.');
    }

    public function destroy(Purchase $purchase): RedirectResponse
    {
        $this->purchaseAdmin->deletePurchase($purchase);

        return redirect()->route('admin.purchases.index')->with('status', 'Purchase deleted.');
    }
}
