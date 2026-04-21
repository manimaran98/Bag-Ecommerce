<?php

declare(strict_types=1);

namespace App\Auth;

use App\Hashing\Sha256Hasher;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;

/**
 * Extends the default Eloquent provider to transparently upgrade legacy
 * SHA-256 password hashes to bcrypt on a successful login.
 *
 * Flow on Auth::attempt():
 *   1. Try bcrypt check (default driver) — passes for already-upgraded accounts.
 *   2. If the stored hash is an old s256$ hash, verify it with Sha256Hasher.
 *   3. On match, immediately rehash with bcrypt and persist — user is upgraded.
 */
final class Sha256FallbackUserProvider extends EloquentUserProvider
{
    private Sha256Hasher $legacyHasher;

    public function __construct(mixed $hasher, string $model)
    {
        parent::__construct($hasher, $model);
        $this->legacyHasher = new Sha256Hasher;
    }

    public function validateCredentials(Authenticatable $user, array $credentials): bool
    {
        $plain = $credentials['password'];
        $hashed = $user->getAuthPassword();

        // Already on bcrypt — normal path.
        if (Hash::check($plain, $hashed)) {
            return true;
        }

        // Legacy sha256 — verify and rehash to bcrypt transparently.
        if ($this->legacyHasher->check($plain, $hashed)) {
            $user->forceFill(['password' => Hash::make($plain)])->save();

            return true;
        }

        return false;
    }
}
