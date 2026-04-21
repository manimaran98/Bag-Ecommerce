<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Services\StripeCheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private StripeCheckoutService $stripe
    ) {}

    public function stripe(Request $request): RedirectResponse
    {
        $result = $this->stripe->createCheckoutSession($request->user());
        if (isset($result['error'])) {
            return redirect()->route('cart.index')->with('error', $result['error']);
        }

        return redirect()->away($result['url']);
    }

    public function return(Request $request): RedirectResponse
    {
        $sessionId = $request->query('session_id');
        if (! is_string($sessionId) || $sessionId === '') {
            return redirect()->route('cart.index')->with('error', 'Missing payment session.');
        }

        return $this->stripe->handleReturn($sessionId);
    }
}
