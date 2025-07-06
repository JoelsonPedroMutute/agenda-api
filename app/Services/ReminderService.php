<?php

namespace App\Services;

use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Models\Appointment;

/**
 * Serviço responsável por lidar com toda a lógica de negócio
 * relacionada aos lembretes (Reminders).
 */
class ReminderService
{
    /**
     * Recupera todos os lembretes pertencentes aos compromissos do usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param mixed $filter Instância do filtro ReminderFilter.
     * @param int $perPage Paginação (itens por página).
     * @param bool $withRelations Define se carrega ou não os relacionamentos.
     * @return LengthAwarePaginator
     */
    public function getAll(int $userId, $filter, int $perPage = 10, bool $withRelations = true): LengthAwarePaginator
    {
        $query = Reminder::whereHas('appointment', function ($query) use ($userId) {
            $query->where('user_id', $userId); // apenas compromissos do usuário
        });

        if ($withRelations) {
            $query->with('appointment'); // carrega relacionamento se solicitado
        }

        return $query->filter($filter)->paginate($perPage);
    }

    /**
     * Cria um novo lembrete para um compromisso do usuário autenticado.
     */
    public function create(int $userId, array $data): Reminder
    {
        $appointment = auth()->user()->appointments()->findOrFail($data['appointment_id']);
        return $appointment->reminders()->create($data);
    }

    /**
     * Recupera um lembrete por ID, desde que esteja vinculado a um compromisso do usuário autenticado.
     */
    public function find(int $userId, int $id): Reminder
    {
        return Reminder::whereHas('appointment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->findOrFail($id);
    }

    /**
     * Atualiza um lembrete pertencente ao usuário autenticado.
     */
    public function update(int $userId, int $id, array $data): Reminder
    {
        $reminder = $this->find($userId, $id);
        $reminder->update($data);

        return $reminder;
    }

    /**
     * Remove (soft delete) um lembrete do usuário autenticado.
     */
    public function delete(int $userId, int $id): void
    {
        $reminder = $this->find($userId, $id);
        $reminder->delete();
    }

    // ------------------------
    //  Ações exclusivas do ADMIN
    // ------------------------

    /**
     * [ADMIN] Lista todos os lembretes com filtros e paginação.
     *
     * @param mixed $filter Instância de ReminderFilter.
     * @param int $perPage Paginação.
     * @param bool $withRelations Define se carrega ou não os relacionamentos.
     * @return LengthAwarePaginator
     */
    public function getAllAdmin($filter, int $perPage = 10, bool $withRelations = true): LengthAwarePaginator
    {
        $query = Reminder::query();

        if ($withRelations) {
            $query->with('appointment.user'); // carrega relacionamentos se solicitado
        }

        return $query->filter($filter)->paginate($perPage);
    }

    /**
     * [ADMIN] Cria um lembrete para qualquer compromisso existente.
     */
    public function createAdmin(array $data): Reminder
    {
        $appointment = Appointment::findOrFail($data['appointment_id']);
        return $appointment->reminders()->create($data);
    }

    /**
     * [ADMIN] Recupera qualquer lembrete pelo ID com relacionamentos.
     */
    public function findAdmin(int $id): Reminder
    {
        return Reminder::with('appointment.user')->findOrFail($id);
    }

    /**
     * [ADMIN] Atualiza qualquer lembrete.
     */
    public function updateAdmin(int $id, array $data): Reminder
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->update($data);

        return $reminder;
    }

    /**
     * [ADMIN] Remove qualquer lembrete (soft delete).
     */
    public function deleteAdmin(int $id): void
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();
    }
}
