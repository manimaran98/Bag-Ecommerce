<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\URL;
use Stripe\Checkout\Session;
use Stripe\Stripe;

final class StripeCheckoutService
{
    public function __construct(
        private CartService $cart,
        private OrderCompletionService $orders
    ) {}

    public function isConfigured(): bool
    {
        $k = config('services.stripe.secret');

        return is_string($k) && $k !== '';
    }

    /**
     * @return array{url: string, payment_id: string}|array{error: string}
     */
    public function createCheckoutSession(User $user): array
    {
        if (! $this->isConfigured()) {
            return ['error' => 'Stripe is not configured. Set STRIPE_SECRET_KEY in .env'];
        }

        $pack = $this->cart->stripeLineItemsFor($user);
        if ($pack['invalid']) {
            return ['error' => 'Invalid cart line (price or quantity). Remove the item and try again.'];
        }
        if ($pack['lines'] === [] || $pack['total'] <= 0) {
            return ['error' => 'Your cart is empty.'];
        }

        Stripe::setApiKey((string) config('services.stripe.secret'));

        $paymentId = 'RST-'.strtoupper(bin2hex(random_bytes(8)));
        $orderTotalStr = number_format($pack['total'], 2, '.', '');

        $successUrl = URL::route('stripe.return', [], true).'?session_id={CHECKOUT_SESSION_ID}';
        $cancelUrl = URL::route('cart.index', ['canceled' => 1], true);

        $session = Session::create([
            'mode' => 'payment',
            'client_reference_id' => $paymentId,
            'line_items' => $pack['lines'],
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'metadata' => [
                'payment_id' => $paymentId,
                'user_id' => (string) $user->id,
                'order_total' => $orderTotalStr,
            ],
        ]);

        $url = $session->url ?? '';
        if ($url === '') {
            return ['error' => 'Could not start checkout.'];
        }

        return ['url' => $url, 'payment_id' => $paymentId];
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function handleReturn(string $sessionId): RedirectResponse
    {
        if (! $this->isConfigured()) {
            return redirect()->route('cart.index')
                ->with('error', 'Stripe is not configured.');
        }

        if (! auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Session expired. Log in; if you were charged, contact support with your Stripe receipt.');
        }

        Stripe::setApiKey((string) config('services.stripe.secret'));

        try {
            $checkoutSession = Session::retrieve($sessionId);
        } catch (\Throwable) {
            return redirect()->route('cart.index')
                ->with('error', 'Could not verify payment with Stripe.');
        }

        if ($checkoutSession->payment_status !== 'paid') {
            return redirect()->route('cart.index')
                ->with('error', 'Payment was not completed.');
        }

        $meta = $checkoutSession->metadata;
        $metaUserId = (int) ($meta->user_id ?? 0);
        $paymentId = (string) ($meta->payment_id ?? '');
        $orderTotal = (string) ($meta->order_total ?? '0');

        $user = auth()->user();
        if (! $user || (int) $user->id !== $metaUserId || $paymentId === '') {
            return redirect()->route('cart.index')
                ->with('error', 'Order does not match your account.');
        }

        if (Purchase::query()->where('stripe_checkout_session_id', $sessionId)->exists()) {
            return redirect()->route('orders.index', ['paid' => 1]);
        }

        $expectedCents = (int) round((float) $orderTotal * 100);
        $paidCents = (int) $checkoutSession->amount_total;
        if ($expectedCents > 0 && abs($paidCents - $expectedCents) > 1) {
            return redirect()->route('cart.index')
                ->with('error', 'Payment amount does not match your cart.');
        }

        $pi = $checkoutSession->payment_intent;
        $piId = is_string($pi) ? $pi : (is_object($pi) && isset($pi->id) ? (string) $pi->id : '');

        $itemDate = now()->format('Y-m-d');
        try {
            $ok = $this->orders->completeFromCart(
                $user,
                $paymentId,
                $orderTotal,
                $itemDate,
                'Approved',
                null,
                $sessionId,
                $piId
            );
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')
                ->with('error', 'One or more items sold out before your order completed. Your payment will be refunded — contact support with payment ID: '.$paymentId);
        }

        if (! $ok) {
            return redirect()->route('cart.index')
                ->with('error', 'Could not save your order. Contact support with your payment ID: '.$paymentId);
        }

        return redirect()->route('orders.index', ['paid' => 1]);
    }
}
