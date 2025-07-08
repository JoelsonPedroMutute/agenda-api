<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Esta classe transforma um modelo Reminder (lembrete)
 * em um array JSON bem estruturado para retorno via API.
 *
 * Pode ser usada sozinha ou dentro de AppointmentResource.
 */
class ReminderResource extends JsonResource
{
    /**
     * Transforma o lembrete em um array.
     * Este método é chamado automaticamente quando retornamos
     * um `ReminderResource` em uma resposta JSON.
     */
   public function toArray(Request $request): array
{
    return [
        // ID do lembrete
        'id' => $this->id,

        // ID do compromisso ao qual o lembrete pertence
        'appointment_id' => $this->appointment_id,

        // Data e hora do lembrete
        'remind_at' => optional($this->remind_at)->format('Y-m-d H:i:s'),

        // Método de notificação: email, sms ou notification
        'method' => $this->method,

        // Status da SMS retornado pela Twilio (se aplicável)
        'message_status' => $this->message_status,

        // SID da SMS retornado pela Twilio (se aplicável)
        'message_sid'    => $this->message_sid,

        // Dados do compromisso (se carregado)
        'appointment' => new AppointmentResource($this->whenLoaded('appointment')),
    ];
}

}
