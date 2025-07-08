<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Classe usada para validar os dados enviados
 * ao criar um novo lembrete (reminder).
 *
 * Aplica regras para garantir integridade e coerência dos dados.
 */
class StoreReminderRequest extends FormRequest
{
    /**
     * Autoriza a requisição.
     * Como qualquer usuário autenticado pode criar lembretes, retornamos true.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação que serão aplicadas ao criar um lembrete.
     *
     * Campos esperados:
     * - appointment_id: obrigatório, deve existir na tabela appointments.
     * - remind_at: obrigatório, deve ser uma data válida e não pode ser no passado.
     * - method: obrigatório, deve ser uma das opções permitidas (email, sms, notification).
     */
    public function rules(): array
    {
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'remind_at'      => 'required|date|after_or_equal:now',
            'method'         => 'required|in:email,message,notification',
        ];
    }
}
