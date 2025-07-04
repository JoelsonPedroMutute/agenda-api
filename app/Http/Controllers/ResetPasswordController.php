<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Realiza a redefinição de senha com base no token enviado por e-mail.
     *
     * Etapas:
     * - Valida os campos da requisição.
     * - Usa o serviço de senha do Laravel para verificar o token e redefinir.
     * - Se a redefinição for bem-sucedida, atualiza a senha e gera novo token "remember".
     */
    public function reset(Request $request)
    {
        // Validação dos campos obrigatórios
        $request->validate([
            'email'    => 'required|email',
            'token'    => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ]);

        // Processa a redefinição de senha
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Atualiza a senha do usuário e gera um novo token de sessão
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        // Retorna mensagem com base no sucesso ou falha da operação
        return response()->json([
            'message' => __($status)
        ], $status === Password::PASSWORD_RESET ? 200 : 400);
    }
}
