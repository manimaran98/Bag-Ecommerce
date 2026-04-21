<?php

namespace App\DataTransferObjects;

final readonly class ChatResult
{
    public function __construct(
        public bool $ok,
        public ?string $text = null,
        public ?string $error = null,
    ) {}

    public static function success(string $text): self
    {
        return new self(true, $text, null);
    }

    public static function failure(string $error): self
    {
        return new self(false, null, $error);
    }
}
