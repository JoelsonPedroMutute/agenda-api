<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreReminderRequest",
 *     type="object",
 *     required={"title", "description", "reminder_time"},
 *     @OA\Property(property="title", type="string", example="Tomar medicamento"),
 *     @OA\Property(property="description", type="string", example="Tomar remédio para dor às 16h"),
 *     @OA\Property(property="reminder_time", type="string", format="date-time", example="2025-07-01T16:00:00Z")
 * )
 */
class StoreReminderRequest {}
