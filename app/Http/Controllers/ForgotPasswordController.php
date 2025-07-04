<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Envia o link de redefinição de senha para o e-mail informado.
     *
     * Etapas:
     * - Valida se o campo `email` foi enviado corretamente.
     * - Utiliza o `Password` do Laravel para enviar o link.
     * - Retorna uma mensagem com o status da operação.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Valida se o campo email é obrigatório e válido
        $request->validate(['email' => 'required|email']);

        // Solicita o envio do link de redefinição
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Retorna a resposta com base no resultado
        return response()->json([
            'message' => __($status)
        ], $status === Password::RESET_LINK_SENT ? 200 : 400);
    }
}
