<?php

namespace App\Services;

use App\Models\Appointment;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Camada de serviço responsável por conter toda a lógica de negócio
 * relacionada à entidade Appointment (Compromissos).
 */
class AppointmentService
{
    /**
     * Retorna todos os compromissos do usuário autenticado com paginação e filtros aplicados.
     */
    public function getAll(int $userId, $filter, int $perPage = 10, bool $withRelations = true): LengthAwarePaginator
    {
        $query = Appointment::where('user_id', $userId)->filter($filter);

        if ($withRelations) {
            $query->with('reminders');
        }

        return $query->paginate($perPage);
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
     * Retorna um compromisso específico que pertence ao usuário autenticado.
     * Lança exceção se o compromisso não existir ou não pertencer ao usuário.
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
     * Atualiza os dados de um compromisso pertencente ao usuário autenticado.
     */
    public function update(int $userId, int $id, array $data): Appointment
    {
        $appointment = $this->find($userId, $id);
        $appointment->update($data);

        return $appointment;
    }

    /**
     * Realiza soft delete de um compromisso pertencente ao usuário autenticado.
     */
    public function delete(int $userId, int $id): void
    {
        $appointment = $this->find($userId, $id);
        $appointment->delete();
    }

    /**
     * [ADMIN] Retorna um compromisso (mesmo deletado) por ID, independente do usuário.
     * Lança ModelNotFoundException se não existir nem entre deletados.
     */
public function findAsAdmin(int $id): Appointment
{
    $appointment = Appointment::withTrashed()
        ->with('user', 'reminders')
        ->find($id);

    if (!$appointment) {
        // Personaliza a mensagem ao invés da genérica "No query results for model..."
        throw new ModelNotFoundException("O compromisso com ID {$id} não foi encontrado.");
    }

    return $appointment;
}

    /**
     * [ADMIN] Atualiza os dados de um compromisso de qualquer usuário.
     */
    public function updateAsAdmin(int $id, array $data): Appointment
    {
        $appointment = $this->findAsAdmin($id);
        $appointment->update($data);

        return $appointment;
    }

    /**
     * [ADMIN] Deleta (soft delete) um compromisso de qualquer usuário.
     * Ignora se o compromisso já estiver deletado.
     */
    public function deleteAsAdmin(int $id): void
    {
        $appointment = $this->findAsAdmin($id);

        if ($appointment->trashed()) {
            // já deletado, não faz nada
            return;
        }

        $appointment->delete();
    }

    /**
     * [ADMIN] Lista todos os compromissos com filtros e paginação.
     */
    public function getAllAsAdmin($filter, int $perPage = 10, bool $withRelations = true): LengthAwarePaginator
    {
        $query = Appointment::filter($filter);

        if ($withRelations) {
            $query->with('user', 'reminders');
        }

        return $query->paginate($perPage);
    }
}
