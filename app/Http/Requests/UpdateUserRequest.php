<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Esta classe é responsável por validar os dados enviados
 * ao atualizar as informações de um usuário (tanto por ele mesmo quanto por um admin).
 *
 * As validações são aplicadas automaticamente no controller ao usar esta classe como tipo do parâmetro.
 */
class UpdateUserRequest extends FormRequest
{
    /**
     * Autoriza a execução da requisição.
     * Neste caso, retornamos `true` pois a lógica de autorização é tratada no controller.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Define as regras de validação para atualizar os dados de um usuário.
     * 
     * Todos os campos usam `sometimes`, o que significa:
     * → O campo será validado **apenas se estiver presente na requisição**.
     */
    public function rules(): array
    {
        return [
            // O nome é obrigatório se for enviado; deve ser texto e no máximo 255 caracteres.
            'name' => 'sometimes|required|string|max:255',

            // O e-mail é obrigatório se for enviado, precisa ser único entre os usuários,
            // mas ignora o e-mail do próprio usuário para evitar falso positivo de duplicação.
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->route('id')),
            ],

            // A senha é opcional (nullable), mas se enviada, deve ter no mínimo 8 caracteres e estar confirmada.
            'password' => 'nullable|string|min:8|confirmed',
        ];
    }

    protected function prepareForValidation()
{
    // Se nenhum dos campos esperados estiver presente, lança uma exceção de validação
    if (!$this->hasAny(['name', 'email', 'password'])) {
        abort(response()->json([
            'message' => 'Você deve enviar ao menos um campo para atualização.',
        ], 422));
    }
}


    /**
     * Define mensagens personalizadas para os erros de validação.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string'   => 'O nome deve ser um texto.',
            'name.max'      => 'O nome não pode ultrapassar 255 caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email'    => 'Informe um e-mail válido.',
            'email.unique'   => 'Este e-mail já está sendo usado por outro usuário.',

            'password.min'       => 'A nova senha deve ter no mínimo 8 caracteres.',
            'password.confirmed' => 'A confirmação da senha não corresponde.',
        ];
    }
}
