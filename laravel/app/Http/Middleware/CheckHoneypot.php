<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Honeypot bot trap — zero friction for real users, catches simple bots.
 *
 * Expects a hidden field named "website" to be present but empty.
 * Real browsers render the field but users never see or fill it (CSS hides it).
 * Bots that blindly fill every input will populate it and get silently rejected.
 *
 * Also enforces a minimum submission time: forms submitted in under 1.5 seconds
 * are almost certainly automated.
 */
final class CheckHoneypot
{
    public function handle(Request $request, Closure $next): Response
    {
        // Field was filled — bot.
        if ($request->input('website', '') !== '') {
            return $this->silentReject($request);
        }

        // Submitted too fast — bot. The _form_ts hidden field is set by the view.
        $ts = (int) $request->input('_form_ts', 0);
        if ($ts > 0 && (time() - $ts) < 2) {
            return $this->silentReject($request);
        }

        return $next($request);
    }

    private function silentReject(Request $request): Response
    {
        // Mimic a successful submission so bots don't know they were blocked.
        return redirect()->back()->withInput()->withErrors([
            'username' => 'Something went wrong. Please try again.',
        ]);
    }
}
