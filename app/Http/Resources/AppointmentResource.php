<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      return [

            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'date'         => $this->date,
            'status'       => $this->status,
            'user'         => [
                'id'    => $this->user->id,
                'name'  => $this->user->name,
                'email' => $this->user->email,]


];

}
}