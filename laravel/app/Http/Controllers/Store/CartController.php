<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
use App\Models\StockInventory;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart
    ) {}

    public function index(Request $request): View
    {
        $lines = $this->cart->linesFor($request->user());
        $total = 0.0;
        foreach ($lines as $line) {
            $total += (int) $line->item_quantity * (float) $line->item_price;
        }

        // Live stock quantities so the view can warn about items that sold out or reduced since cart add.
        $stockIds = array_map(fn ($l) => (int) $l->item_id, $lines);
        $liveQty = $stockIds
            ? StockInventory::query()
                ->whereIn('stock_id', $stockIds)
                ->pluck('stock_quantity', 'stock_id')
                ->map(fn ($q) => (int) $q)
                ->all()
            : [];

        return view('store.cart.index', [
            'lines' => $lines,
            'total' => $total,
            'liveQty' => $liveQty,
        ]);
    }

    public function store(AddToCartRequest $request, int $stock): RedirectResponse
    {
        $result = $this->cart->addLine($request->user(), $stock, (int) $request->validated('quantity'));

        if (! $result['ok']) {
            return redirect()->route('products.show', $stock)->with('error', $result['message'] ?? 'Could not add to cart.');
        }

        return redirect()->route('products.index')->with('status', 'Item added to cart.');
    }

    public function destroy(Request $request, string $item): RedirectResponse
    {
        $this->cart->removeLine($request->user(), $item);

        return redirect()->route('cart.index')->with('status', 'Item removed.');
    }
}
