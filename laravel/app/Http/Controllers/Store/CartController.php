<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddToCartRequest;
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

        return view('store.cart.index', [
            'lines' => $lines,
            'total' => $total,
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
