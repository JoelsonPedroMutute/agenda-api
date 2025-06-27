<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    /**
     * Listar todos os usuários com seus agendamentos e lembretes.
     */
    public function index()
    {
        return UserResource::collection(
            User::with('appointments.reminders')->paginate(10)
        );
    }

    /**
     * Criar um novo usuário.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => ['required', 'string', Password::defaults()],
            'role' => 'in:user,admin',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] ?? 'user',
        ]);

        return new UserResource($user);
    }

    /**
     * Exibir um usuário específico com seus relacionamentos.
     */
    public function show(User $user)
    {
        return new UserResource($user->load('appointments.reminders'));
    }

    /**
     * Atualizar os dados de um usuário.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|unique:users,email,' . $user->id,
            'role' => 'sometimes|in:user,admin',
        ]);

        $user->update($validated);

        return new UserResource($user);
    }

    /**
     * Deletar um usuário.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'message' => 'Usuário deletado com sucesso.'
        ], 200);
    }
}
