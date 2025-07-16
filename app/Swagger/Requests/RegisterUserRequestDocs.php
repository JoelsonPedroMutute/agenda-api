<?php

namespace App\Swagger\Requests;

/**
 * @OA\RequestBody(
 *     request="RegisterUserRequest",
 *     description="Dados necessários para registrar um novo usuário",
 *     required=true,
 *     @OA\JsonContent(
 *         required={"name", "email", "password", "password_confirmation"},
 *         @OA\Property(
 *             property="name",
 *             type="string",
 *             maxLength=255,
 *             example="Joelson Mutute"
 *         ),
 *         @OA\Property(
 *             property="email",
 *             type="string",
 *             format="email",
 *             example="joelson@email.com"
 *         ),
 *         @OA\Property(
 *             property="password",
 *             type="string",
 *             format="password",
 *             minLength=8,
 *             example="senhaSegura123"
 *         ),
 *         @OA\Property(
 *             property="password_confirmation",
 *             type="string",
 *             format="password",
 *             example="senhaSegura123"
 *         ),
 *         @OA\Property(
 *             property="role",
 *             type="string",
 *             enum={"user", "admin"},
 *             example="user",
 *             description="Papel do usuário (opcional). Padrão: 'user'"
 *         )
 *     )
 * )
 */
class RegisterUserRequestDocs {}
