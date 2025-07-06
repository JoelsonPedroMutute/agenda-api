<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Serviço responsável por lidar com regras de negócio
 * relacionadas ao usuário autenticado.
 */
class UserService
{
    /**
     * Atualiza os dados do usuário autenticado.
     *
     * @param User $user Instância do usuário que será atualizado.
     * @param array $data Dados validados do formulário.
     * @return User Usuário atualizado.
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update($data); // Atualiza nome, email, etc.
        return $user;
    }

    /**
     * Altera a senha do usuário autenticado.
     *
     * @param User $user Instância do usuário.
     * @param string $newPassword Nova senha em texto plano.
     * @return User Usuário com senha atualizada.
     */
    public function changePassword(User $user, string $newPassword): User
    {
        $user->update([
            'password' => Hash::make($newPassword), // Garante criptografia segura.
        ]);

        return $user;
    }

    /**
     * Recupera todos os usuários com ou sem relacionamentos, conforme parâmetro.
     *
     * @param bool $withRelations Define se deve carregar appointments e reminders.
     * @param int $perPage Quantidade por página.
     * @return LengthAwarePaginator
     */
    public function getAll(bool $withRelations, int $perPage = 10): LengthAwarePaginator
    {
        return $withRelations
            ? User::with('appointments.reminders')->paginate($perPage)
            : User::paginate($perPage);
    }

    /**
     * Recupera um usuário por ID, com ou sem relacionamentos.
     *
     * @param int $id ID do usuário.
     * @param bool $withRelations Define se deve carregar appointments e reminders.
     * @return User
     */
    public function findById(int $id, bool $withRelations = true): User
    {
        return $withRelations
            ? User::with('appointments.reminders')->findOrFail($id)
            : User::findOrFail($id);
    }
}
