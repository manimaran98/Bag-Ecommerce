<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Purchase;
use App\Models\User;
use App\Services\OrderCompletionService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

final class ProcessOrderCompletion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 5;

    public function __construct(
        public readonly User $user,
        public readonly string $paymentId,
        public readonly string $orderTotal,
        public readonly string $purchaseDateYmd,
        public readonly string $purchaseValidation,
        public readonly ?string $stripeCheckoutSessionId,
        public readonly ?string $stripePaymentIntentId,
    ) {}

    public function handle(OrderCompletionService $orders): void
    {
        // Fast path: purchase already recorded (e.g. a retry after a transient failure).
        if (Purchase::query()->where('purchase_id', $this->paymentId)->exists()) {
            return;
        }

        // Atomic lock prevents two queued copies of the same payment (e.g. user hitting
        // the Stripe return URL twice) from both running completeFromCart concurrently.
        $lock = Cache::lock("order_lock:{$this->paymentId}", 60);

        if (! $lock->get()) {
            // Another worker holds the lock — this is a duplicate, discard it.
            return;
        }

        try {
            // Re-check after acquiring the lock in case the other worker just finished.
            if (Purchase::query()->where('purchase_id', $this->paymentId)->exists()) {
                return;
            }

            $ok = $orders->completeFromCart(
                $this->user,
                $this->paymentId,
                $this->orderTotal,
                $this->purchaseDateYmd,
                $this->purchaseValidation,
                null,
                $this->stripeCheckoutSessionId,
                $this->stripePaymentIntentId,
            );

            if (! $ok) {
                Log::error('ProcessOrderCompletion: order completion returned false', [
                    'payment_id' => $this->paymentId,
                    'user_id' => $this->user->id,
                ]);
                $this->fail('Order completion service returned false.');

                return;
            }

            Cache::forget("recommendations:user:{$this->user->id}");
        } finally {
            $lock->release();
        }
    }

    public function failed(\Throwable $e): void
    {
        Log::error('ProcessOrderCompletion job failed permanently', [
            'payment_id' => $this->paymentId,
            'user_id' => $this->user->id,
            'error' => $e->getMessage(),
        ]);
    }
}
