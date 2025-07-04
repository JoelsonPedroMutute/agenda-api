<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Esta classe valida os dados enviados na atualização de um lembrete (reminder).
 * 
 * A validação é aplicada automaticamente quando essa classe
 * é usada como parâmetro no método do controller.
 */
class UpdateReminderRequest extends FormRequest
{
    /**
     * Autoriza o envio da requisição.
     * Como qualquer usuário autenticado pode atualizar seus lembretes,
     * retornamos true (a lógica de permissão está no controller ou service).
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação aplicadas na edição de um lembrete.
     *
     * Todas as regras usam `sometimes`, o que significa:
     * → O campo será validado **apenas se estiver presente** na requisição.
     */
    public function rules(): array
    {
        return [
            'appointment_id' => 'sometimes|exists:appointments,id',          // Se enviado, deve ser um ID válido de um compromisso
            'remind_at'      => 'sometimes|date|after_or_equal:now',         // Se enviado, deve ser uma data futura ou agora
            'method'         => 'sometimes|in:email,message,notification',   // Se enviado, deve estar entre os métodos válidos
        ];
    }
}
