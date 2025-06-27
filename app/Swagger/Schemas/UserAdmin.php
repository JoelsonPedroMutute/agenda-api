<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UserAdmin",
 *     type="object",
 *     title="Usuário (Admin)",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Joelson Admin"),
 *     @OA\Property(property="email", type="string", example="admin@example.com"),
 *     @OA\Property(property="role", type="string", example="admin"),
 *     @OA\Property(
 *         property="appointments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/AppointmentAdmin")
 *     )
 * )
 */
class UserAdmin {}
