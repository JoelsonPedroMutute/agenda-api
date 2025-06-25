<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentService
{
    /**
     * Retorna todos os compromissos do usuário autenticado.
     */
    public function getAll(int $userId, $filter, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::where('user_id', $userId)
    ->with('reminders') // carrega os reminders junto
    ->filter($filter)
    ->paginate($perPage);

    }

    /**
     * Cria um novo compromisso.
     */
    public function create(int $userId, array $data): Appointment
    {
        $data['user_id'] = $userId;
        return Appointment::create($data);
    }

    /**
     * Retorna um compromisso específico do usuário.
     */
   public function find(int $userId, int $id): Appointment
{
    $appointment = Appointment::where('user_id', $userId)
                              ->where('id', $id)
                              ->first();

    if (!$appointment) {
        throw new ModelNotFoundException("Este compromisso não pertence ao usuário autenticado.");
    }

    return $appointment;
}
    /**
     * Atualiza um compromisso.
     */
    public function update(int $userId, int $id, array $data): Appointment
    {
        $appointment = $this->find($userId, $id);
        $appointment->update($data);
        return $appointment;
    }

    /**
     * Remove um compromisso.
     */
    public function delete(int $userId, int $id): void
    {
        $appointment = $this->find($userId, $id);
        $appointment->delete();
    }
}
