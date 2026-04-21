<?php

namespace App\Services\Chat;

use App\Contracts\ChatProviderInterface;
use App\DataTransferObjects\ChatResult;
use Illuminate\Support\Facades\Http;

final class GeminiChatProvider implements ChatProviderInterface
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $model
    ) {}

    public function name(): string
    {
        return 'gemini';
    }

    /**
     * @param  list<array{role: string, content: string}>  $messages
     */
    public function complete(string $systemPrompt, array $messages): ChatResult
    {
        if ($this->apiKey === '') {
            return ChatResult::failure('Gemini is not configured.');
        }

        $contents = [];
        foreach ($messages as $m) {
            $role = $m['role'] === 'assistant' ? 'model' : 'user';
            $contents[] = [
                'role' => $role,
                'parts' => [['text' => $m['content']]],
            ];
        }

        $url = sprintf(
            'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent',
            rawurlencode($this->model)
        );

        $response = Http::timeout(60)
            ->withOptions(['query' => ['key' => $this->apiKey]])
            ->post($url, [
                'systemInstruction' => [
                    'parts' => [['text' => $systemPrompt]],
                ],
                'contents' => $contents,
            ]);

        if (! $response->successful()) {
            return ChatResult::failure($this->formatHttpError($response->status(), $response->body()));
        }

        $data = $response->json();
        $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
        if (! is_string($text) || $text === '') {
            return ChatResult::failure('Empty response from Gemini.');
        }

        return ChatResult::success($text);
    }

    private function formatHttpError(int $status, string $body): string
    {
        if ($status === 429) {
            return 'rate_limited';
        }
        if ($status >= 500) {
            return 'server_error';
        }

        return 'gemini_error';
    }
}
