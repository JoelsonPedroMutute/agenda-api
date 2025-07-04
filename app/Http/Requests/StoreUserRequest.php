<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe responsável por validar os dados enviados
 * ao criar um novo usuário pela área administrativa (admin).
 *
 * Utilizada quando um ADMIN cria usuários via painel ou API.
 */
class StoreUserRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado a realizar esta requisição.
     * Como a verificação de permissão geralmente é feita no controller,
     * aqui retornamos `true` por padrão.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação para os campos enviados.
     *
     * Campos esperados:
     * - name: obrigatório, texto, máximo 100 caracteres.
     * - email: obrigatório, formato de e-mail válido e único.
     * - password: obrigatório, texto, no mínimo 6 caracteres e com confirmação.
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ];
    }
}
