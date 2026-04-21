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
    }
}
