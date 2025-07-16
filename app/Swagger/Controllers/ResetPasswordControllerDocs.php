<?php

namespace App\Swagger\Controllers;

/**
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Redefinição de senha"
 * )
 */
class ResetPasswordControllerDocs
{
    /**
     * @OA\Post(
     *     path="/reset-password",
     *     summary="Redefinir a senha do usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "token", "password", "password_confirmation"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@email.com"),
     *             @OA\Property(property="token", type="string", example="c19f20543f8a50b5ffcc5957..."),
     *             @OA\Property(property="password", type="string", format="password", example="nova_senha_segura"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="nova_senha_segura")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Senha redefinida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sua senha foi redefinida!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro na redefinição de senha",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Este token de redefinição de senha é inválido.")
     *         )
     *     )
     * )
     */
}
