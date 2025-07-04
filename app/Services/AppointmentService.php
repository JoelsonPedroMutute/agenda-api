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
     *
     * @param int $userId ID do usuário autenticado.
     * @param mixed $filter Instância de filtro (AppointmentFilter).
     * @param int $perPage Quantidade de itens por página.
     * @return LengthAwarePaginator
     */
    public function getAll(int $userId, $filter, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::where('user_id', $userId)
            ->with('reminders') // eager loading dos lembretes
            ->filter($filter)   // aplica filtros se existirem
            ->paginate($perPage);
    }

    /**
     * Cria um novo compromisso para o usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param array $data Dados validados do compromisso.
     * @return Appointment
     */
    public function create(int $userId, array $data): Appointment
    {
        $data['user_id'] = $userId; // força o user_id a ser o usuário autenticado
        return Appointment::create($data);
    }

    /**
     * Retorna um compromisso específico que pertence ao usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param int $id ID do compromisso.
     * @return Appointment
     *
     * @throws ModelNotFoundException se o compromisso não for encontrado ou não pertencer ao usuário.
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
     *
     * @param int $userId ID do usuário autenticado.
     * @param int $id ID do compromisso.
     * @param array $data Dados validados.
     * @return Appointment
     */
    public function update(int $userId, int $id, array $data): Appointment
    {
        $appointment = $this->find($userId, $id); // verifica se o user é dono
        $appointment->update($data);

        return $appointment;
    }

    /**
     * Realiza soft delete de um compromisso pertencente ao usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param int $id ID do compromisso.
     */
    public function delete(int $userId, int $id): void
    {
        $appointment = $this->find($userId, $id);
        $appointment->delete();
    }

    /**
     * [ADMIN] Retorna um compromisso por ID, independente do usuário.
     *
     * @param int $id ID do compromisso.
     * @return Appointment
     *
     * @throws ModelNotFoundException se o compromisso não for encontrado.
     */
    public function findByIdAsAdmin(int $id): Appointment
    {
        return Appointment::with('user', 'reminders')->findOrFail($id);
    }

    /**
     * [ADMIN] Lista todos os compromissos com filtros e paginação.
     *
     * @param mixed $filter Filtro a ser aplicado (AppointmentFilter).
     * @param int $perPage Quantidade por página.
     * @return LengthAwarePaginator
     */
    public function getAllAsAdmin($filter, int $perPage = 10): LengthAwarePaginator
    {
        return Appointment::with('user', 'reminders')
            ->filter($filter)
            ->paginate($perPage);
    }

    /**
     * [ADMIN] Deleta (soft delete) um compromisso de qualquer usuário.
     *
     * @param int $id ID do compromisso.
     */
    public function deleteAsAdmin(int $id): void
    {
        $appointment = $this->findByIdAsAdmin($id);
        $appointment->delete();
    }
}

