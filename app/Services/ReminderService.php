<?php

namespace App\Services;

use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Models\Appointment;

class ReminderService
{
    public function getAll(int $userId, $filter, int $perPage = 10): LengthAwarePaginator
    {
        return Reminder::whereHas('appointment', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->filter($filter)
            ->paginate($perPage);
    }

    public function create(int $userId, array $data): Reminder
    {
        $appointment = auth()->user()->appointments()->findOrFail($data['appointment_id']);
        return $appointment->reminders()->create($data);
    }

    public function find(int $userId, int $id): Reminder
    {
        return Reminder::whereHas('appointment', function ($query) use ($userId) {
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

    /**
     * Métodos para Admin
     */

    public function getAllAdmin($filter, int $perPage = 10): LengthAwarePaginator
    {
        return Reminder::with('appointment.user')
            ->filter($filter)
            ->paginate($perPage);
    }

    public function createAdmin(array $data): Reminder
    {
        // Certifica-se de que o appointment existe
        $appointment = Appointment::findOrFail($data['appointment_id']);
        return $appointment->reminders()->create($data);
    }

    public function findAdmin(int $id): Reminder
    {
        return Reminder::with('appointment.user')->findOrFail($id);
    }

    public function updateAdmin(int $id, array $data): Reminder
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->update($data);
        return $reminder;
    }

    public function deleteAdmin(int $id): void
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();
    }
}
