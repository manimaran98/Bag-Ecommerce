<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Delivery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class DeliveryController extends Controller
{
    public function index(): View
    {
        $deliveries = Delivery::query()->with('user')->orderByDesc('delivery_id')->paginate(25);

        return view('admin.deliveries.index', compact('deliveries'));
    }

    public function edit(Delivery $delivery): View
    {
        return view('admin.deliveries.edit', ['delivery' => $delivery]);
    }

    public function update(Request $request, Delivery $delivery): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:users,id'],
            'purchase_id' => ['required', 'string', 'max:100', 'exists:purchase,purchase_id'],
            'delivery_agent' => ['required', 'string', 'max:100'],
            'delivery_status' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:100'],
            'payment_status' => ['required', 'string', 'max:100'],
        ]);

        $delivery->fill($data);
        $delivery->save();

        return redirect()->route('admin.deliveries.index')->with('status', 'Order updated.');
    }

    public function destroy(Delivery $delivery): RedirectResponse
    {
        $delivery->delete();

        return redirect()->route('admin.deliveries.index')->with('status', 'Order row deleted.');
    }
}
