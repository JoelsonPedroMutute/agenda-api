<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ChangePasswordRequest",
 *     type="object",
 *     required={"current_password", "new_password"},
 *     @OA\Property(property="current_password", type="string", example="senha_antiga123"),
 *     @OA\Property(property="new_password", type="string", example="novaSenhaSegura456")
 * )
 */
class ChangePasswordRequest {}
