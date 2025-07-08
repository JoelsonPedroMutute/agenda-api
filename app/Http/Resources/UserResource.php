<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * UserResource
 * 
 * Esta classe transforma o modelo User em uma resposta JSON padronizada.
 * Ela é usada para garantir que a saída dos dados do usuário seja
 * clara, segura e estruturada.
 */
class UserResource extends JsonResource
{
    /**
     * Transforma o recurso User em um array para resposta JSON.
     */
    public function toArray(Request $request): array
{
    return [
        'id'            => $this->id,
        'name'          => $this->name,
        'email'         => $this->email,
        'role'          => $this->role,
        'phone_number'  => $this->phone_number, // ✅ Adicionado aqui
        'appointments'  => AppointmentResource::collection($this->whenLoaded('appointments')),
    ];
}

}
