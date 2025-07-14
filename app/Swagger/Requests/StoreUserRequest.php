<?php

namespace App\Swagger\Requests;

use OpenApi\Annotations as OA;

/**
 * @OA\RequestBody(
 *     request="StoreUserRequest",
 *     required=true,
 *     description="Dados necessários para criação de um novo usuário (admin)",
 *     @OA\JsonContent(
 *         required={"name", "email", "password", "password_confirmation"},
 *         @OA\Property(property="name", type="string", example="Maria Silva"),
 *         @OA\Property(property="email", type="string", example="maria@email.com"),
 *         @OA\Property(property="password", type="string", format="password", example="12345678"),
 *         @OA\Property(property="password_confirmation", type="string", format="password", example="12345678"),
 *         @OA\Property(property="role", type="string", enum={"user", "admin"}, example="user")
 *     )
 * )
 */
class StoreUserRequest
{
}
