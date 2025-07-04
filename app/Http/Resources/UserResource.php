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
            // Identificador único do usuário
            'id'    => $this->id,

            // Nome do usuário
            'name'  => $this->name,

            // Endereço de email do usuário
            'email' => $this->email,

            // Papel do usuário: 'user' ou 'admin'
            'role'  => $this->role,

            // Lista de compromissos (appointments) associados ao usuário
            // Só será incluída se tiver sido carregada com with('appointments')
            'appointments' => AppointmentResource::collection($this->whenLoaded('appointments')),
        ];
    }
}
