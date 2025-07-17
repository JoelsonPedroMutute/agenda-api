<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Esta classe é responsável por validar os dados enviados
 * ao atualizar um compromisso existente.
 *
 * Ela é aplicada automaticamente no controller quando usada como parâmetro.
 */
class UpdateAppointmentRequest extends FormRequest
{
    /**
     * Autoriza o envio da requisição.
     * Neste caso, como a lógica de permissão está no controller/service,
     * retorna true para liberar a validação.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação aplicadas na atualização do compromisso.
     *
     * Campos:
     * - title: obrigatório, string, no máximo 255 caracteres.
     * - description: opcional (nullable), string.
     * - date: obrigatório, deve ser uma data válida.
     * - start_time: obrigatório, no formato de hora (HH:mm).
     * - end_time: obrigatório, formato de hora e deve ser depois de start_time.
     * - status: opcional, mas se informado, deve ser um dos valores permitidos.
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'start_time'  => 'required|date_format:H:i',                 // Exemplo: "08:00"
            'end_time'    => 'required|date_format:H:i|after:start_time',// Deve ser após o horário de início
            'status'      => ['nullable', 'in:ativo,cancelado,concluido'], // Valores permitidos
        ];
    }
}
