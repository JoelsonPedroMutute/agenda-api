<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $service;

    /**
     * Aplica autenticação Sanctum e injeta o serviço do usuário.
     */
    public function __construct(UserService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * Retorna os dados do usuário autenticado, incluindo compromissos e lembretes.
     */
    public function show(Request $request)
    {
        $user = $request->user()->load('appointments.reminders');

        return response()->json([
            'message' => 'Dados do usuário recuperados com sucesso.',
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Atualiza os dados do próprio usuário autenticado.
     */
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $this->service->updateUser($user, $request->validated());

        return response()->json([
            'message' => 'Dados atualizados com sucesso.',
            'user' => new UserResource($user),
        ], 200);
    }

    /**
     * Altera a senha do usuário autenticado.
     * Verifica se a senha atual está correta antes de alterar.
     */
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Senha atual incorreta.'], 422);
        }

        $this->service->changePassword($user, $request->new_password);

        return response()->json(['message' => 'Senha alterada com sucesso.'], 200);
    }

    /**
     * Exclui (soft delete) a própria conta do usuário autenticado.
     */
    public function destroySelf(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'Conta deletada com sucesso.'], 204);
    }

    /**
     * Lista todos os usuários da aplicação com seus relacionamentos.
     * Ação disponível apenas para administradores.
     */
    public function index()
    {
        $this->authorizeAdmin();

        return response()->json([
            'message' => 'Lista de usuários recuperada com sucesso.',
            'users' => UserResource::collection(
                User::with('appointments.reminders')->paginate(10)
            ),
        ], 200);
    }

    /**
     * Lista todos os usuários sem agendamentos nem lembretes.
     * Ação disponível apenas para administradores.
     */
    public function indexWithoutRelations()
    {
        $this->authorizeAdmin();

        return response()->json([
            'message' => 'Lista de usuários sem relações recuperada com sucesso.',
            'users' => UserResource::collection(User::paginate(10)),
        ], 200);
    }

    /**
     * Cria um novo usuário na aplicação (apenas admin).
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'string', Password::defaults()],
            'role'     => 'in:user,admin',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'] ?? 'user',
        ]);

        return response()->json([
            'message' => 'Usuário criado com sucesso.',
            'user' => new UserResource($user)
        ], 201);
    }

    /**
     * Exibe os dados de um usuário específico pelo ID (apenas admin).
     */
    public function showById($id)
    {
        $this->authorizeAdmin();

        $user = User::with('appointments.reminders')->findOrFail($id);

        return response()->json([
            'message' => 'Usuário encontrado com sucesso.',
            'user' => new UserResource($user),
        ], 200);
    }

    /**
     * Atualiza os dados de um usuário específico pelo ID (apenas admin).
     */
    public function updateById(Request $request, $id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'role'  => 'sometimes|in:user,admin',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Usuário atualizado com sucesso.',
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * Exclui (soft delete) um usuário pelo ID (apenas admin).
     */
    public function destroyById($id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso.'], 204);
    }

    /**
     * Restaura um usuário deletado via soft delete (apenas admin).
     */
    public function restoreById($id)
    {
        $this->authorizeAdmin();

        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
            return response()->json(['message' => 'Usuário restaurado com sucesso.'], 200);
        }

        return response()->json(['message' => 'Usuário não está deletado.'], 400);
    }

    /**
     * Verifica se o usuário atual é um administrador.
     * Caso não seja, interrompe a requisição com erro 403.
     */
    protected function authorizeAdmin(): void
    {
        $user = auth()->user();
        if (!$user || !$user->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, 'Ação permitida apenas para administradores.');
        }
    }
}
