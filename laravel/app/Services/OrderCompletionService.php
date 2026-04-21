<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Delivery;
use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\StockInventory;
use App\Models\User;
use Illuminate\Support\Facades\DB;

final class OrderCompletionService
{
    /**
     * Persists purchase, purchase_item rows, delivery, decrements stock, clears cart.
     * Mirrors legacy bag_complete_cart_order (transactional).
     */
    public function completeFromCart(
        User $user,
        string $paymentId,
        string $totalPrice,
        string $purchaseDateYmd,
        string $purchaseValidation,
        ?string $paymentResit,
        ?string $stripeCheckoutSessionId,
        ?string $stripePaymentIntentId
    ): bool {
        return (bool) DB::transaction(function () use (
            $user,
            $paymentId,
            $totalPrice,
            $purchaseDateYmd,
            $purchaseValidation,
            $paymentResit,
            $stripeCheckoutSessionId,
            $stripePaymentIntentId
        ) {
            $paymentResit = $paymentResit ?? '';
            $stripeCheckoutSessionId = $stripeCheckoutSessionId ?? '';
            $stripePaymentIntentId = $stripePaymentIntentId ?? '';

            Purchase::query()->create([
                'purchase_id' => $paymentId,
                'id' => $user->id,
                'total_price' => $totalPrice,
                'purchase_date' => $purchaseDateYmd,
                'payment_resit' => $paymentResit,
                'purchase_validation' => $purchaseValidation,
                'stripe_checkout_session_id' => $stripeCheckoutSessionId !== '' ? $stripeCheckoutSessionId : null,
                'stripe_payment_intent_id' => $stripePaymentIntentId !== '' ? $stripePaymentIntentId : null,
            ]);

            $cartRows = CartItem::query()->where('id', $user->id)->get();
            foreach ($cartRows as $row2) {
                $stockId = (int) $row2->item_id;
                $qty = (int) $row2->item_quantity;

                PurchaseItem::query()->create([
                    'purchase_id' => $paymentId,
                    'id' => $user->id,
                    'stock_id' => $stockId,
                    'stock_img' => $row2->item_img,
                    'stock_name' => $row2->item_name,
                    'stock_quantity' => $qty,
                    'stock_price' => (string) $row2->item_price,
                    'purchase_date' => $purchaseDateYmd,
                ]);

                $stock = StockInventory::query()->where('stock_id', $stockId)->lockForUpdate()->first();
                if ($stock) {
                    $newQty = max(0, (int) $stock->stock_quantity - $qty);
                    $stock->stock_quantity = $newQty;
                    $stock->save();
                }
            }

            $address = (string) ($user->address ?? '');
            $paymentStatus = $purchaseValidation === 'Approved' ? 'Approved' : 'Processing';

            Delivery::query()->create([
                'id' => $user->id,
                'purchase_id' => $paymentId,
                'delivery_agent' => 'Processing',
                'delivery_status' => 'Processing',
                'address' => $address,
                'payment_status' => $paymentStatus,
            ]);

            CartItem::query()->where('id', $user->id)->delete();

            return true;
        });
    }
}
