<?php

declare(strict_types=1);

namespace App\Hashing;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;

/**
 * Legacy SHA-256 hasher kept only for verifying old hashes during transparent rehash-to-bcrypt.
 * New passwords are never hashed with this driver — the default driver is bcrypt.
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

    public function needsRehash(string $hashedValue, array $options = []): bool
    {
        // Any sha256 hash should be upgraded to bcrypt on next successful login.
        return str_starts_with($hashedValue, self::PREFIX);
    }
}
