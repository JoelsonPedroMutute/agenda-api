<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Realiza o registro de um novo usuário.
     * Pode ser registrado como "user" (padrão) ou "admin" (se enviado explicitamente).
     */
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated();

        // Define o role como 'user' caso não seja enviado
        $data['role'] = $request->input('role', 'user');

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        // Cria o token de autenticação com Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Realiza o login do usuário com e-mail e senha.
     * Retorna token de autenticação em caso de sucesso.
     */
    public function login(Request $request)
    {
        // Validação dos campos obrigatórios
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Busca o usuário pelo e-mail
        $user = User::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha está correta
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        // Gera novo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login feito com sucesso.',
            'user'    => $user,
            'token'   => $token,
        ], 200);
    }

    /**
     * Faz logout do usuário autenticado removendo o token atual.
     */
    public function logout(Request $request)
    {
        $user = $request->user();

        // Verifica se o usuário está autenticado
        if (! $user) {
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        }

        // Revoga o token atual
        $user->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso'], 200);
    }
}
