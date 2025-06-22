<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function updateUser(User $user, array $data): User
    {
        $user->update($data);
        return $user;
    }

    public function changePassword(User $user, string $newPassword): User
    {
        $user->update([
            'password' => bcrypt($newPassword),
        ]);
        return $user;
    }
}
