<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Atualiza os dados do usuário.
     */
    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    /**
     * Altera a senha do usuário, utilizando hash seguro.
     */
    public function changePassword(User $user, string $newPassword): User
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        return $user;
    }
}
