<?php

namespace App\Http\Middleware;

use App\Services\CaptchaVerifier;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class VerifyCaptchaToken
{
    public function __construct(
        private readonly CaptchaVerifier $captcha
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! config('ai_chat.enabled', true)) {
            return $next($request);
        }

        if (! $this->captcha->captchaConfigured()) {
            return $next($request);
        }

        if ($request->user() && ! config('ai_chat.captcha.require_for_authenticated', false)) {
            return $next($request);
        }

        $token = $request->input('captcha_token');
        if (! is_string($token) || $token === '') {
            return response()->json(['message' => 'CAPTCHA verification required.'], 422);
        }

        $driver = (string) config('ai_chat.captcha.driver', 'turnstile');
        $ip = $request->ip();
        $ok = match ($driver) {
            'recaptcha' => $this->captcha->verifyRecaptchaV3($token, $ip),
            default => $this->captcha->verifyTurnstile($token, $ip),
        };

        if (! $ok) {
            return response()->json(['message' => 'CAPTCHA verification failed.'], 422);
        }

        return $next($request);
    }
}
