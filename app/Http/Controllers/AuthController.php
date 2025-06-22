<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterUserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Registrar novo usuário
     */
    public function register(RegisterUserRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token', [
            'post:read',
            'post:create',
            'post:update',
            'post:delete',
        ])->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    /**
     * Fazer login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login feito com sucesso.',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    /**
     * Fazer logout
     */
    public function logout(Request $request)
    {
        $accessToken = $request->bearerToken();
        $token = PersonalAccessToken::findToken($accessToken);

        if (! $token) {
            return response()->json(['message' => 'Token inválido ou não autenticado'], 401);
        }

        $token->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }
}
