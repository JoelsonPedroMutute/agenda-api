<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe responsável por validar os dados enviados
 * ao criar um novo compromisso (appointment).
 *
 * Essa validação é aplicada automaticamente no controller
 * quando essa classe é usada como parâmetro.
 */
class StoreAppointmentRequest extends FormRequest
{
    /**
     * Define se o usuário está autorizado a fazer esta requisição.
     * Como qualquer usuário autenticado pode criar um compromisso, retorna true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação que serão aplicadas aos dados da requisição.
     *
     * Campos:
     * - title: obrigatório, texto, máximo 255 caracteres.
     * - description: opcional (nullable), texto.
     * - date: obrigatório, deve ser uma data válida.
     * - start_time: obrigatório, formato de hora (HH:mm), ex: "08:30".
     * - end_time: obrigatório, formato de hora (HH:mm).
     * - status: opcional, mas se enviado deve ser um dos valores permitidos.
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'start_time'  => 'required|date_format:H:i',       // Exemplo: "14:30"
            'end_time'    => 'required|date_format:H:i',
            'status'      => ['nullable', 'in:ativo,cancelado,concluído'],
        ];
    }
}
