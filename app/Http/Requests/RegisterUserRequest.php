<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'in:user,admin', // ← valida apenas valores válidos
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'O email informado já está cadastrado.',
            'role.in'      => 'O papel do usuário deve ser "user" ou "admin".',
        ];
    }
}
