<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Classe responsável por transformar um modelo Appointment
 * em um array formatado para ser retornado como resposta JSON.
 *
 * Ela é usada com `return new AppointmentResource($appointment)`
 * ou `return AppointmentResource::collection($appointments)`
 */
class AppointmentResource extends JsonResource
{
    /**
     * Transforma o recurso (appointment) em um array.
     *
     * O método toArray é automaticamente chamado quando este recurso é retornado pela API.
     */
    public function toArray(Request $request): array
    {
        return [
            // ID do compromisso
            'id'           => $this->id,

            // Título do compromisso
            'title'        => $this->title,

            // Descrição do compromisso
            'description'  => $this->description,

            // Data do compromisso
            'date'         => $this->date,

            // Hora de início (formato HH:mm)
            'start_time'   => $this->start_time,

            // Hora de término (formato HH:mm)
            'end_time'     => $this->end_time,

            // Status do compromisso (ativo, cancelado, concluído)
            'status'       => $this->status,

            // Usuário associado ao compromisso
            // Só será carregado se tiver sido carregado com with('user')
            'user'         => new UserResource($this->whenLoaded('user')),

            // Lista de lembretes associados ao compromisso
            // Só será carregada se tiver sido carregada com with('reminders')
            'reminders'    => ReminderResource::collection($this->whenLoaded('reminders')),
        ];
    }
}
