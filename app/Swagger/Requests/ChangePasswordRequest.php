<?php

namespace App\Swagger\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="ChangePasswordRequest",
 *     required=true,
 *     description="Requisição para alteração de senha do usuário autenticado",
 *     @OA\JsonContent(
 *         required={"current_password", "new_password", "new_password_confirmation"},
 *         @OA\Property(property="current_password", type="string", format="password", example="senhaAtual123"),
 *         @OA\Property(property="new_password", type="string", format="password", example="novaSenha123"),
 *         @OA\Property(property="new_password_confirmation", type="string", format="password", example="novaSenha123")
 *     )
 * )
 */
class ChangePasswordRequest
{
}
