<?php

namespace App\Providers;

use App\Models\Purchase;
use App\Policies\PurchasePolicy;
use App\Services\Chat\GeminiChatProvider;
use App\Services\Chat\GroqChatProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use App\Auth\Sha256FallbackUserProvider;
use App\Hashing\Sha256Hasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(GeminiChatProvider::class, function (): GeminiChatProvider {
            return new GeminiChatProvider(
                (string) config('ai_chat.gemini.api_key'),
                (string) config('ai_chat.gemini.model'),
            );
        });

        $this->app->singleton(GroqChatProvider::class, function (): GroqChatProvider {
            return new GroqChatProvider(
                (string) config('ai_chat.groq.api_key'),
                (string) config('ai_chat.groq.model'),
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register legacy sha256 driver for completeness (still used by Sha256FallbackUserProvider).
        Hash::extend('sha256', fn (array $config = []): Sha256Hasher => new Sha256Hasher);

        // Custom user provider: verifies bcrypt normally, falls back to sha256 for
        // legacy accounts and rehashes them to bcrypt on first successful login.
        Auth::provider('sha256-fallback', function ($app, array $config): Sha256FallbackUserProvider {
            return new Sha256FallbackUserProvider($app['hash'], $config['model']);
        });

        Paginator::useBootstrapFive();

        Gate::policy(Purchase::class, PurchasePolicy::class);

        RateLimiter::for('chat', function (Request $request) {
            if ($request->user()) {
                return Limit::perMinute((int) config('ai_chat.throttle.chat_authenticated_per_minute', 60))
                    ->by('chat-user-'.$request->user()->id);
            }

            return Limit::perMinute((int) config('ai_chat.throttle.chat_guest_per_minute', 20))
                ->by('chat-ip-'.$request->ip());
        });

        RateLimiter::for('support', fn (Request $request) => Limit::perMinute((int) config('ai_chat.throttle.support_per_minute', 5))
            ->by('support-ip-'.$request->ip()));

        // 5 login attempts per minute per IP, then locked for 1 minute.
        RateLimiter::for('login', fn (Request $request) => Limit::perMinute(5)
            ->by('login-ip-'.$request->ip()));

        // 10 registration attempts per hour per IP to block account-farming bots.
        RateLimiter::for('register', fn (Request $request) => Limit::perHour(10)
            ->by('register-ip-'.$request->ip()));

        // 10 checkout attempts per minute per authenticated user.
        RateLimiter::for('checkout', fn (Request $request) => Limit::perMinute(10)
            ->by('checkout-user-'.($request->user()?->id ?? $request->ip())));

        // 30 product search/browse requests per minute per IP (generous for legit browsing).
        RateLimiter::for('browse', fn (Request $request) => Limit::perMinute(30)
            ->by('browse-ip-'.$request->ip()));
    }
}
