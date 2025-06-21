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

    'id'          => $this->id,
    'user_id'     => $this->user_id, 
    'title'       => $this->title,
    'description' => $this->description,
    'date'        => $this->date,
    'start_time'  => $this->start_time,
    'end_time'    => $this->end_time,
    'status'      => $this->status,
    'created_at'  => $this->created_at->toDateTimeString(),
    'updated_at'  => $this->updated_at->toDateTimeString(),

];

}
}