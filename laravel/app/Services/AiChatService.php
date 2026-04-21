<?php

namespace App\Services;

use App\Contracts\ChatProviderInterface;
use App\DataTransferObjects\ChatResult;
use App\Models\StockInventory;
use App\Services\Chat\GeminiChatProvider;
use App\Services\Chat\GroqChatProvider;

final class AiChatService
{
    public function __construct(
        private readonly GeminiChatProvider $gemini,
        private readonly GroqChatProvider $groq
    ) {}

    /**
     * @param  list<array{role: string, content: string}>  $messages
     */
    public function reply(array $messages): ChatResult
    {
        if (! config('ai_chat.enabled', true)) {
            return ChatResult::failure('disabled');
        }

        $system = $this->buildSystemPrompt();

        $paid = strtolower((string) config('ai_chat.tier', 'free')) === 'paid';

        if ($paid) {
            return $this->tryGeminiWithRetry($system, $messages);
        }

        return $this->runFreeTier($system, $messages);
    }

    /**
     * @param  list<array{role: string, content: string}>  $messages
     */
    private function runFreeTier(string $system, array $messages): ChatResult
    {
        $primary = strtolower((string) config('ai_chat.primary_when_free', 'groq'));
        $fallback = filter_var(config('ai_chat.fallback_enabled', true), FILTER_VALIDATE_BOOL);

        $first = $primary === 'gemini' ? $this->gemini : $this->groq;
        $second = $primary === 'gemini' ? $this->groq : $this->gemini;

        $r = $first->complete($system, $messages);
        if ($r->ok) {
            return $r;
        }

        if (! $fallback) {
            return $this->friendlyFailure($r);
        }

        if ($this->shouldRetryWithOtherProvider($r->error ?? '')) {
            $r2 = $second->complete($system, $messages);
            if ($r2->ok) {
                return $r2;
            }

            return $this->friendlyFailure($r2);
        }

        return $this->friendlyFailure($r);
    }

    /**
     * @param  list<array{role: string, content: string}>  $messages
     */
    private function tryGeminiWithRetry(string $system, array $messages): ChatResult
    {
        $r = $this->gemini->complete($system, $messages);
        if ($r->ok) {
            return $r;
        }

        if (! in_array($r->error ?? '', ['rate_limited', 'server_error', 'gemini_error'], true)) {
            return $this->friendlyFailure($r);
        }

        $r2 = $this->gemini->complete($system, $messages);
        if ($r2->ok) {
            return $r2;
        }

        return $this->friendlyFailure($r2);
    }

    private function shouldRetryWithOtherProvider(string $error): bool
    {
        return in_array($error, ['rate_limited', 'server_error', 'gemini_error', 'groq_error'], true);
    }

    private function friendlyFailure(ChatResult $r): ChatResult
    {
        return ChatResult::failure('Chat is temporarily unavailable. Please try again or use Help desk.');
    }

    private function buildSystemPrompt(): string
    {
        $name = config('app.name', 'HalenMiaga');
        $lines = [
            "You are a helpful shopping assistant for {$name}, an online bag shop.",
            'Keep answers concise. You can help with products, cart, checkout, and orders in general terms.',
            'If the user needs account-specific help or human support, tell them to use the "Talk to help desk" button in the chat.',
            'Do not invent order numbers or payment details.',
        ];

        try {
            $count = StockInventory::query()->where('stock_quantity', '>', 0)->count();
            $cats = StockInventory::query()->distinct()->pluck('stock_category')->filter()->take(8)->implode(', ');
            if ($count > 0) {
                $lines[] = "Catalog hint: about {$count} in-stock products. Categories include: {$cats}.";
            }
        } catch (\Throwable) {
            // ignore DB errors in prompt
        }

        return implode("\n", $lines);
    }

    public function isConfigured(): bool
    {
        $paid = strtolower((string) config('ai_chat.tier', 'free')) === 'paid';

        if ($paid) {
            return (string) config('ai_chat.gemini.api_key') !== '';
        }

        $hasGemini = (string) config('ai_chat.gemini.api_key') !== '';
        $hasGroq = (string) config('ai_chat.groq.api_key') !== '';

        return $hasGemini || $hasGroq;
    }
}
