<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="UpdateReminderRequest",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Tomar vitamina"),
 *     @OA\Property(property="description", type="string", example="Vitamina C às 17h"),
 *     @OA\Property(property="reminder_time", type="string", format="date-time", example="2025-07-01T17:00:00Z")
 * )
 */
class UpdateReminderRequest {}
