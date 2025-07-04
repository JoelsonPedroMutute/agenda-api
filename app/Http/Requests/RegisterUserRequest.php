<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Esta classe é responsável por validar os dados enviados
 * durante o processo de registro de um novo usuário.
 *
 * Ela será utilizada automaticamente pelo controller
 * quando for injetada como parâmetro.
 */
class RegisterUserRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado a fazer essa requisição.
     * Neste caso, qualquer visitante pode se registrar, então retorna true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação para os campos enviados no cadastro.
     *
     * Campos esperados:
     * - name: obrigatório, texto, máximo 255 caracteres.
     * - email: obrigatório, formato de e-mail, e único na tabela users.
     * - password: obrigatório, mínimo de 8 caracteres, e com confirmação.
     * - role: opcional, mas se enviado deve ser 'user' ou 'admin'.
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'in:user,admin', // ← Aceita apenas os valores válidos para o tipo de usuário
        ];
    }

    /**
     * Mensagens personalizadas de erro para os campos validados.
     *
     * Caso o email já esteja em uso, ou o valor de role seja inválido,
     * essas mensagens serão retornadas.
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'O email informado já está cadastrado.',
            'role.in'      => 'O papel do usuário deve ser "user" ou "admin".',
        ];
    }
}
