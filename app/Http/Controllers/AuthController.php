<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|confirmed|min:6',
        ], [
            'email.unique' => 'O e-mail informado já está cadastrado.',
        ]);


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
            'message' => 'Login feito com sucesso.',
            'user'  => $user,
            'token' => $token,
        ], 201);
    }
    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['As credenciais estão incorretas.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user'  => $user,
            'token' => $token,
        ]);
    }


public function logout(Request $request)
{
    // Pega o token Bearer do cabeçalho da requisição
    $accessToken = $request->bearerToken();

    // Verifica se o token é válido
    $token = PersonalAccessToken::findToken($accessToken);

    if (!$token) {
        return response()->json(['message' => 'Token inválido ou não autenticado'], 401);
    }

    // Deleta o token (logout efetivo)
    $token->delete();

    return response()->json(['message' => 'Logout realizado com sucesso']);
}



}
