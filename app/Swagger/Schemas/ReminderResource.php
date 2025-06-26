<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReminderResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="appointment_id", type="integer", example=3),
 *     @OA\Property(property="title", type="string", example="Tomar medicamento"),
 *     @OA\Property(property="description", type="string", example="Tomar remédio para dor"),
 *     @OA\Property(property="reminder_time", type="string", format="date-time", example="2025-07-01T16:00:00Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-25T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-25T10:00:00Z")
 * )
 */
class ReminderResource {}
