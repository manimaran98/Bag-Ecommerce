<?php

declare(strict_types=1);

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

/**
 * Stores passwords as SHA-256(salt || password) with a random 32-byte salt per password.
 * Format: s256$<64 hex salt>$<64 hex digest>
 */
final class Sha256Hasher implements HasherContract
{
    private const PREFIX = 's256$';

    public function info(#[\SensitiveParameter] string $hashedValue): array
    {
        return [
            'algo' => 0,
            'algoName' => 'sha256-salted',
            'options' => [],
        ];
    }

    public function make(#[\SensitiveParameter] string $value, array $options = []): string
    {
        $salt = random_bytes(32);

        return self::PREFIX.bin2hex($salt).'$'.hash('sha256', $salt.$value);
    }

    public function check(#[\SensitiveParameter] string $value, string $hashedValue): bool
    {
        if ($hashedValue === '' || ! str_starts_with($hashedValue, self::PREFIX)) {
            return false;
        }

        if (! preg_match('/^s256\$(.{64})\$(.{64})$/', $hashedValue, $m)) {
            return false;
        }

        $salt = @hex2bin($m[1]);
        if ($salt === false || strlen($salt) !== 32) {
            return false;
        }

        return hash_equals(hash('sha256', $salt.$value), $m[2]);
    }

    public function needsRehash(string $hashedValue): bool
    {
        return false;
    }
}
