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

    public function __construct(UserService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    //  Dados do usuário autenticado
    public function show(Request $request)
    {
        $user = $request->user()->load('appointments.reminders');
        return new UserResource($user);
    }

    //  Atualiza dados do próprio usuário
    public function update(UpdateUserRequest $request)
    {
        $user = $request->user();
        $this->service->updateUser($user, $request->validated());

        return response()->json([
            'message' => 'Dados atualizados com sucesso.',
            'user' => new UserResource($user),
        ]);
    }

    //  Altera senha
    public function changePassword(ChangePasswordRequest $request)
    {
        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Senha atual incorreta.'], 422);
        }

        $this->service->changePassword($user, $request->new_password);

        return response()->json(['message' => 'Senha alterada com sucesso.']);
    }

    //  Exclui a própria conta (soft delete)
    public function destroySelf(Request $request)
    {
        $user = $request->user();
        $user->delete();

        return response()->json(['message' => 'Conta deletada com sucesso.']);
    }

    //  LISTAR todos os usuários (apenas admin)
    public function index()
    {
        $this->authorizeAdmin();

        return UserResource::collection(
            User::with('appointments.reminders')->paginate(10)
        );
    }

    //  CRIAR novo usuário (admin)
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

    //  MOSTRAR um usuário pelo ID (admin)
    public function showById($id)
    {
        $this->authorizeAdmin();

        $user = User::with('appointments.reminders')->findOrFail($id);
        return new UserResource($user);
    }

    //  ATUALIZAR um usuário (admin)
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

        return new UserResource($user);
    }

    //  EXCLUIR um usuário (admin)
    public function destroyById($id)
    {
        $this->authorizeAdmin();

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'Usuário deletado com sucesso.']);
    }

    //  RESTAURAR usuário soft-deletado (admin)
    public function restoreById($id)
    {
        $this->authorizeAdmin();

        $user = User::withTrashed()->findOrFail($id);

        if ($user->trashed()) {
            $user->restore();
            return response()->json(['message' => 'Usuário restaurado com sucesso.']);
        }

        return response()->json(['message' => 'Usuário não está deletado.'], 400);
    }

    //  Verifica se o usuário é admin
  protected function authorizeAdmin(): void
{
    
    $user = auth()->user();
    if (!$user || !$user->isAdmin()) {
        abort(Response::HTTP_FORBIDDEN, 'Ação permitida apenas para administradores.');
    }
}
}