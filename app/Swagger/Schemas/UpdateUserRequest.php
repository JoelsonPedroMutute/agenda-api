<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateUserRequest",
 *     type="object",
 *     @OA\Property(property="name", type="string", example="Joelson Mutute"),
 *     @OA\Property(property="email", type="string", format="email", example="joelson@example.com")
 * )
 */
class UpdateUserRequest {}
