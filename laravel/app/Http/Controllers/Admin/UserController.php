<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class UserController extends Controller
{
    public function index(): View
    {
        $users = User::query()->orderBy('id')->get();

        return view('admin.users.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'name' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:100', 'unique:users,contact'],
            'address' => ['required', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::query()->create([
            'username' => $data['username'],
            'name' => $data['name'],
            'contact' => $data['contact'],
            'address' => $data['address'],
            'password' => $data['password'],
        ]);

        return redirect()->route('admin.users.index')->with('status', 'User created.');
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', ['user' => $user]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $user->id],
            'name' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:100', 'unique:users,contact,' . $user->id],
            'address' => ['required', 'string', 'max:100'],
            'new_password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->username = $data['username'];
        $user->name = $data['name'];
        $user->contact = $data['contact'];
        $user->address = $data['address'];
        if (! empty($data['new_password'])) {
            $user->password = $data['new_password'];
        }
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User updated.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('error', 'Cannot delete the admin account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('status', 'User deleted.');
    }
}
