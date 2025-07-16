<?php

namespace App\Swagger\Controllers;

/**
 * @OA\Tag(
 *     name="Autenticação",
 *     description="Recuperação de senha"
 * )
 */
class ForgotPasswordControllerDocs
{
    /**
     * @OA\Post(
     *     path="/forgot-password",
     *     summary="Enviar link de redefinição de senha",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="usuario@email.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Link de redefinição enviado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nós enviamos o link de redefinição de senha para seu e-mail.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erro ao enviar o link",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Não foi possível enviar o link de redefinição de senha.")
     *         )
     *     )
     * )
     */
}
