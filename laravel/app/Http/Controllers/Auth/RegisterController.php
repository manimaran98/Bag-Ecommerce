<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function create(): View
    {
        return view('store.auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = User::query()->create($request->safe()->only(['username', 'name', 'address', 'contact', 'password']));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
    }
}
