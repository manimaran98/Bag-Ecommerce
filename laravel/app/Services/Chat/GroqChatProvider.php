<?php

namespace App\Services\Chat;

use App\Contracts\ChatProviderInterface;
use App\DataTransferObjects\ChatResult;
use Illuminate\Support\Facades\Http;

final class GroqChatProvider implements ChatProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model
    ) {}

    public function name(): string
    {
        return 'groq';
    }

    /**
     * @param  list<array{role: string, content: string}>  $messages
     */
    public function complete(string $systemPrompt, array $messages): ChatResult
    {
        if ($this->apiKey === '') {
            return ChatResult::failure('Groq is not configured.');
        }

        $openAiMessages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($messages as $m) {
            $openAiMessages[] = [
                'role' => $m['role'] === 'assistant' ? 'assistant' : 'user',
                'content' => $m['content'],
            ];
        }

        $response = Http::timeout(60)
            ->withToken($this->apiKey)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $openAiMessages,
            ]);

        if (! $response->successful()) {
            return ChatResult::failure($this->formatHttpError($response->status()));
        }

        $data = $response->json();
        $text = $data['choices'][0]['message']['content'] ?? null;
        if (! is_string($text) || $text === '') {
            return ChatResult::failure('Empty response from Groq.');
        }

        return ChatResult::success($text);
    }

    private function formatHttpError(int $status): string
    {
        if ($status === 429) {
            return 'rate_limited';
        }
        if ($status >= 500) {
            return 'server_error';
        }

        return 'groq_error';
    }
}
