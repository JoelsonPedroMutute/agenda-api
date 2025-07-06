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
use App\Filters\UserFilter; // ✅ Importa a classe de filtro dedicada

/**
 * Controlador responsável pela gestão de usuários.
 * Inclui operações para usuários autenticados (perfil) e administradores (gestão completa).
 */
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
     * Retorna os dados do usuário autenticado, com ou sem os relacionamentos.
     */
    public function show(Request $request)
    {
        $withRelations = $request->boolean('with_relations', true);
        $user = $request->user();

        if ($withRelations) {
            $user->load('appointments.reminders');
        }

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
     * Permite que o usuário autenticado altere sua senha.
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
     * Soft delete da própria conta do usuário autenticado.
     */
    public function destroySelf(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'Conta deletada com sucesso.'], 204);
    }

    /**
     * Lista os usuários do sistema com ou sem relacionamentos.
     * Agora também suporta listagem de deletados com `trashed=only|with`.
     * Acesso restrito a administradores.
     */
    public function index(Request $request)
    {
        $this->authorizeAdmin();

        // ✅ Aplicação do filtro de usuários usando a classe dedicada
        $filter = new UserFilter($request);
        $query = $filter->apply(User::query());

        // Paginação final (mantida como no seu código original)
        $users = $query->paginate(10);

        return response()->json([
            'message' => 'Lista de usuários recuperada com sucesso.',
            'users' => UserResource::collection($users),
        ], 200);
    }

    /**
     * Cria um novo usuário (admin only).
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
     * Exibe os dados de um usuário específico pelo ID.
     * Pode incluir relacionamentos, se solicitado.
     * Acesso restrito a administradores.
     */
    public function showById(Request $request, $id)
    {
        $this->authorizeAdmin();

        $withRelations = $request->boolean('with_relations', true);
        $user = $withRelations
            ? User::with('appointments.reminders')->findOrFail($id)
            : User::findOrFail($id);

        return response()->json([
            'message' => 'Usuário encontrado com sucesso.',
            'user' => new UserResource($user),
        ], 200);
    }

    /**
     * Atualiza os dados de um usuário específico pelo ID.
     * Acesso restrito a administradores.
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
     * Deleta (soft delete) um usuário pelo ID.
     * Acesso restrito a administradores.
     * Personalizado para evitar erro padrão ao deletar um usuário já deletado.
     */
    public function destroyById($id)
    {
        $this->authorizeAdmin();

        // Busca inclusive os usuários já deletados
        $user = User::withTrashed()->find($id);

        // Se o usuário não existir, retorna erro personalizado
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não encontrado.',
            ], 404);
        }

        // Se o usuário já estiver deletado, retorna aviso
        if ($user->trashed()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário já foi deletado anteriormente.',
            ], 409); // 409 = Conflict
        }

        // Caso contrário, faz o soft delete
        $user->delete();

        // 204 No Content => não retorna corpo JSON
        return response()->noContent();
    }

    /**
     * Verifica se o usuário autenticado é um administrador.
     * Caso contrário, aborta com erro 403 (Forbidden).
     */
    protected function authorizeAdmin(): void
    {
        $user = auth()->user();

        if (!$user || !$user->isAdmin()) {
            abort(Response::HTTP_FORBIDDEN, 'Ação permitida apenas para administradores.');
        }
    }
}