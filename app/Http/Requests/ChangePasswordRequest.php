<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe responsável por validar os dados enviados
 * quando um usuário tenta alterar sua senha.
 *
 * Essa validação é aplicada automaticamente no controller
 * que utilizar esse Form Request.
 */
class ChangePasswordRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a realizar a requisição.
     *
     * Neste caso, como estamos lidando com o próprio usuário,
     * retornamos `true` (permitido para qualquer usuário autenticado).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação para os campos enviados na requisição.
     *
     * Regras aplicadas:
     * - current_password: obrigatório e deve ser uma string.
     * - new_password: obrigatório, string, mínimo de 8 caracteres e deve ter confirmação.
     */
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password'     => 'required|string|min:8|confirmed',
        ];
    }
}
