<?php

namespace App\Contracts;

use App\DataTransferObjects\ChatResult;

interface ChatProviderInterface
{
    /**
     * @param  list<array{role: string, content: string}>  $messages
     */
    public function complete(string $systemPrompt, array $messages): ChatResult;

    public function name(): string;
}
