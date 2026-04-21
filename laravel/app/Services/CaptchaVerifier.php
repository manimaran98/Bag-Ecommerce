<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

final class CaptchaVerifier
{
    public function verifyTurnstile(string $token, ?string $remoteIp = null): bool
    {
        $secret = (string) config('ai_chat.captcha.secret_key');
        if ($secret === '') {
            return false;
        }

        $response = Http::asForm()->timeout(10)->post(
            'https://challenges.cloudflare.com/turnstile/v0/siteverify',
            array_filter([
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $remoteIp,
            ])
        );

        if (! $response->successful()) {
            return false;
        }

        return (bool) ($response->json('success') ?? false);
    }

    public function verifyRecaptchaV3(string $token, ?string $remoteIp = null): bool
    {
        $secret = (string) config('ai_chat.captcha.recaptcha_secret_key');
        if ($secret === '') {
            return false;
        }

        $response = Http::asForm()->timeout(10)->post(
            'https://www.google.com/recaptcha/api/siteverify',
            array_filter([
                'secret' => $secret,
                'response' => $token,
                'remoteip' => $remoteIp,
            ])
        );

        if (! $response->successful()) {
            return false;
        }

        $data = $response->json();
        $score = (float) ($data['score'] ?? 0);

        return ($data['success'] ?? false) === true && $score >= 0.3;
    }

    public function captchaConfigured(): bool
    {
        $driver = (string) config('ai_chat.captcha.driver', 'turnstile');

        return match ($driver) {
            'recaptcha' => (string) config('ai_chat.captcha.recaptcha_secret_key') !== '',
            default => (string) config('ai_chat.captcha.secret_key') !== '',
        };
    }
}
