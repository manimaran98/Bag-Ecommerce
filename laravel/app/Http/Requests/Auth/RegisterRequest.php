<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, \Illuminate\Validation\Rules\Password|string>>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:100', 'unique:users,username'],
            'name' => ['required', 'string', 'max:100'],
            'contact' => ['required', 'string', 'max:100', 'unique:users,contact'],
            'address' => ['required', 'string', 'max:100'],
            'password' => ['required', 'confirmed', 'string', 'min:6'],
        ];
    }
}
