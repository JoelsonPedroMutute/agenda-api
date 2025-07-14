<?php

namespace App\Swagger\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="UpdateUserRequest",
 *     required=true,
 *     description="Dados para atualizar as informações do usuário. Todos os campos são opcionais, mas pelo menos um deve estar presente.",
 *     @OA\JsonContent(
 *         @OA\Property(property="name", type="string", example="João Atualizado"),
 *         @OA\Property(property="email", type="string", example="joao@atualizado.com"),
 *         @OA\Property(property="password", type="string", format="password", example="novasenha123"),
 *         @OA\Property(property="password_confirmation", type="string", format="password", example="novasenha123")
 *     )
 * )
 */
class UpdateUserRequest
{
}
