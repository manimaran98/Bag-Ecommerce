<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'           => \App\Http\Middleware\EnsureUserIsAdmin::class,
            'captcha'         => \App\Http\Middleware\VerifyCaptchaToken::class,
            'recaptcha.form'  => \App\Http\Middleware\VerifyRecaptchaForm::class,
            'honeypot'        => \App\Http\Middleware\CheckHoneypot::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
