<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\StockInventory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class CartService
{
    /**
     * @return array{ok: bool, message?: string}
     */
    public function addLine(User $user, int $stockId, int $quantity): array
    {
        $stock = StockInventory::query()->where('stock_id', $stockId)->first();
        if (! $stock) {
            return ['ok' => false, 'message' => 'Product not found.'];
        }
        if ($stock->stock_quantity <= 0) {
            return ['ok' => false, 'message' => 'This item is out of stock.'];
        }
        if ($quantity < 1 || $quantity > $stock->stock_quantity) {
            return ['ok' => false, 'message' => 'Invalid quantity for available stock.'];
        }

        $exists = CartItem::query()
            ->where('id', $user->id)
            ->where('item_id', (string) $stockId)
            ->exists();

        if ($exists) {
            return ['ok' => false, 'message' => 'This product is already in your cart.'];
        }

        CartItem::query()->create([
            'id' => $user->id,
            'item_id' => (string) $stockId,
            'item_img' => $stock->stock_img,
            'item_name' => $stock->stock_name,
            'item_price' => (string) $stock->stock_price,
            'item_quantity' => $quantity,
        ]);

        return ['ok' => true];
    }

    public function removeLine(User $user, string $itemId): void
    {
        CartItem::query()
            ->where('id', $user->id)
            ->where('item_id', $itemId)
            ->delete();
    }

    /**
     * @return list<CartItem>
     */
    public function linesFor(User $user): array
    {
        return CartItem::query()
            ->where('id', $user->id)
            ->orderBy('cart_id')
            ->get()
            ->all();
    }

    public function clearForUser(int $userId): void
    {
        CartItem::query()->where('id', $userId)->delete();
    }

    /**
     * @return array{lines: list<array<string, mixed>>, total: float, invalid: bool}
     */
    public function stripeLineItemsFor(User $user): array
    {
        $rows = CartItem::query()->where('id', $user->id)->get();
        $lineItems = [];
        $total = 0.0;
        $invalid = false;

        foreach ($rows as $row) {
            $qty = (int) $row->item_quantity;
            $price = (float) $row->item_price;
            if ($qty < 1 || $price <= 0) {
                $invalid = true;
                break;
            }
            $total += $qty * $price;
            $unitCents = (int) round($price * 100);
            if ($unitCents < 1) {
                $invalid = true;
                break;
            }
            $name = (string) $row->item_name;
            $lineItems[] = [
                'quantity' => $qty,
                'price_data' => [
                    'currency' => 'myr',
                    'unit_amount' => $unitCents,
                    'product_data' => [
                        'name' => function_exists('mb_substr') ? mb_substr($name, 0, 120) : substr($name, 0, 120),
                    ],
                ],
            ];
        }

        return [
            'lines' => $lineItems,
            'total' => $total,
            'invalid' => $invalid,
        ];
    }
}
