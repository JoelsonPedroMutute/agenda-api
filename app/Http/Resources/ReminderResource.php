<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReminderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
 public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'appointment_id' => $this->appointment_id,
            'remind_at'      => $this->remind_at?->format('Y-m-d\TH:i:s'), // ISO 8601
            'method'         => $this->method,
            
            // Inclui o relacionamento, se estiver carregado
            'appointment'    => new AppointmentResource($this->whenLoaded('appointment')),
        ];
    }
}
