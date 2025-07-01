<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AppointmentService
{
    /**
     * Retorna todos os compromissos do usuário autenticado (com filtro e paginação).
     */
    public function getAll(int $userId, $filter, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::where('user_id', $userId)
            ->with('reminders')
            ->filter($filter)
            ->paginate($perPage);
    }

    /**
     * Cria um novo compromisso para o usuário autenticado.
     */
    public function create(int $userId, array $data): Appointment
    {
        $data['user_id'] = $userId;
        return Appointment::create($data);
    }

    /**
     * Retorna um compromisso específico do usuário autenticado.
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
     * Atualiza um compromisso do usuário autenticado.
     */
    public function update(int $userId, int $id, array $data): Appointment
    {
        $appointment = $this->find($userId, $id);
        $appointment->update($data);

        return $appointment;
    }

    /**
     * Remove um compromisso (soft delete) do usuário autenticado.
     */
    public function delete(int $userId, int $id): void
    {
        $appointment = $this->find($userId, $id);
        $appointment->delete();
    }

    /**
     * Para uso exclusivo do admin: encontrar agendamento por ID, independente do user.
     */
    public function findByIdAsAdmin(int $id): Appointment
    {
        return Appointment::with('user', 'reminders')->findOrFail($id);
    }

    /**
     * Para uso exclusivo do admin: listar todos com paginação e filtro.
     */
    public function getAllAsAdmin($filter, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::with('user', 'reminders')
            ->filter($filter)
            ->paginate($perPage);
    }

    /**
     * Para uso exclusivo do admin: deletar qualquer agendamento.
     */
    public function deleteAsAdmin(int $id): void
    {
        $appointment = $this->findByIdAsAdmin($id);
        $appointment->delete();
    }
}
