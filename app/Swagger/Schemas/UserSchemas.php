<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Joelson Mutute"),
 *     @OA\Property(property="email", type="string", format="email", example="joelson@example.com"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2025-06-01T12:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-20T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-22T15:30:00Z")
 * )
 */
class UserSchemas {}
