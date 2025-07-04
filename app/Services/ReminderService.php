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
     * @return LengthAwarePaginator
     */
    public function getAll(int $userId, $filter, int $perPage = 10): LengthAwarePaginator
    {
        return Reminder::whereHas('appointment', function ($query) use ($userId) {
                $query->where('user_id', $userId); // somente compromissos do usuário
            })
            ->filter($filter)
            ->paginate($perPage);
    }

    /**
     * Cria um novo lembrete para um compromisso do usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param array $data Dados validados.
     * @return Reminder
     */
    public function create(int $userId, array $data): Reminder
    {
        // Garante que o compromisso realmente pertença ao usuário
        $appointment = auth()->user()->appointments()->findOrFail($data['appointment_id']);

        return $appointment->reminders()->create($data);
    }

    /**
     * Recupera um lembrete por ID, desde que esteja vinculado a um compromisso do usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param int $id ID do lembrete.
     * @return Reminder
     */
    public function find(int $userId, int $id): Reminder
    {
        return Reminder::whereHas('appointment', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->findOrFail($id);
    }

    /**
     * Atualiza um lembrete pertencente ao usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param int $id ID do lembrete.
     * @param array $data Dados validados.
     * @return Reminder
     */
    public function update(int $userId, int $id, array $data): Reminder
    {
        $reminder = $this->find($userId, $id);
        $reminder->update($data);

        return $reminder;
    }

    /**
     * Remove (soft delete) um lembrete do usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param int $id ID do lembrete.
     */
    public function delete(int $userId, int $id): void
    {
        $reminder = $this->find($userId, $id);
        $reminder->delete();
    }

    // ------------------------
    // 🔒 Ações exclusivas do ADMIN
    // ------------------------

    /**
     * [ADMIN] Lista todos os lembretes da aplicação com filtros e paginação.
     *
     * @param mixed $filter Instância de ReminderFilter.
     * @param int $perPage Paginação.
     * @return LengthAwarePaginator
     */
    public function getAllAdmin($filter, int $perPage = 10): LengthAwarePaginator
    {
        return Reminder::with('appointment.user') // inclui o dono do compromisso
            ->filter($filter)
            ->paginate($perPage);
    }

    /**
     * [ADMIN] Cria um lembrete para qualquer compromisso existente.
     *
     * @param array $data Dados validados.
     * @return Reminder
     */
    public function createAdmin(array $data): Reminder
    {
        $appointment = Appointment::findOrFail($data['appointment_id']);
        return $appointment->reminders()->create($data);
    }

    /**
     * [ADMIN] Recupera qualquer lembrete pelo ID com relacionamentos.
     *
     * @param int $id ID do lembrete.
     * @return Reminder
     */
    public function findAdmin(int $id): Reminder
    {
        return Reminder::with('appointment.user')->findOrFail($id);
    }

    /**
     * [ADMIN] Atualiza qualquer lembrete.
     *
     * @param int $id ID do lembrete.
     * @param array $data Dados validados.
     * @return Reminder
     */
    public function updateAdmin(int $id, array $data): Reminder
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->update($data);

        return $reminder;
    }

    /**
     * [ADMIN] Remove qualquer lembrete (soft delete).
     *
     * @param int $id ID do lembrete.
     */
    public function deleteAdmin(int $id): void
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();
    }
}
