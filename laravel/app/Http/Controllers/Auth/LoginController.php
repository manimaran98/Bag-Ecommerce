<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View
    {
        return view('store.auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $creds = $request->only('username', 'password');

        if (Auth::attempt(['username' => $creds['username'], 'password' => $creds['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            return redirect()->intended(
                $user->isAdmin() ? route('admin.dashboard') : route('home')
            );
        }

        return back()->withErrors(['username' => __('These credentials do not match our records.')])->onlyInput('username');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
