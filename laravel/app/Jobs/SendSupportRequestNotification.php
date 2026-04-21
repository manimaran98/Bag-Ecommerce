<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\SupportRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

final class SendSupportRequestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public int $backoff = 10;

    public function __construct(
        public readonly SupportRequest $supportRequest,
    ) {}

    public function handle(): void
    {
        $adminEmail = config('halenmiaga.admin_email');
        if (! $adminEmail) {
            return;
        }

        $ticket = $this->supportRequest;
        $from = $ticket->guest_name
            ? "{$ticket->guest_name} <{$ticket->guest_email}>"
            : ($ticket->user?->username ?? 'Unknown user');

        Mail::raw(
            "New support request #{$ticket->id}\n\nFrom: {$from}\n\nMessage:\n{$ticket->body}",
            function ($message) use ($adminEmail, $ticket): void {
                $message->to($adminEmail)
                    ->subject("Support Request #{$ticket->id} — ".config('app.name'));
            }
        );
    }

    public function failed(\Throwable $e): void
    {
        Log::warning('SendSupportRequestNotification failed', [
            'support_request_id' => $this->supportRequest->id,
            'error' => $e->getMessage(),
        ]);
    }
}
