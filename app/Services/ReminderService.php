<?php

namespace App\Services;

use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class ReminderService
{

   public function getAll(int $userId, $filter, int $perPage = 10): LengthAwarePaginator
{
    return Reminder::whereHas('appointment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
        ->filter($filter) // ← escopo fora do whereHas
        ->paginate($perPage);
}

     public function create(int $userId, array $data): Reminder
     {
        $appointment = auth()->user()->appointments()->findOrFail($data['appointment_id']);
        return $appointment->reminders()->create($data);
     }


     public function find( int $userId, int $id): Reminder
     {
        return Reminder::whereHas('appointment', function($query) use ($userId){
            $query->where('user_id', $userId);
        })
        ->findOrFail($id);
     }

      public function update(int $userId, int $id, array $data): Reminder
      {
        $reminder = $this->find($userId, $id);
        $reminder->update($data);
        return $reminder;
      }

      public function delete(int $userId, int $id): void
      {
        $reminder = $this->find($userId, $id);
        $reminder->delete();
      }










}