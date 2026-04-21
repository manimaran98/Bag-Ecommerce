<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Services\CaptchaVerifier;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Verifies reCAPTCHA v3 (invisible) on standard HTML form submissions.
 * Returns a redirect-back with validation error on failure — suitable for
 * login, register, and any other Blade form page.
 *
 * Skipped entirely when RECAPTCHA_SECRET_KEY is not configured, so the
 * site works without API keys in development.
 */
final class VerifyRecaptchaForm
{
    public function __construct(
        private readonly CaptchaVerifier $captcha
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $secret = (string) config('ai_chat.captcha.recaptcha_secret_key');
        if ($secret === '') {
            return $next($request);
        }

        $token = $request->input('g-recaptcha-response', '');
        if (! is_string($token) || $token === '') {
            return back()->withInput()->withErrors(['captcha' => 'Bot check failed. Please try again.']);
        }

        if (! $this->captcha->verifyRecaptchaV3($token, $request->ip())) {
            return back()->withInput()->withErrors(['captcha' => 'Bot check failed. Please try again.']);
        }

        return $next($request);
    }
}
