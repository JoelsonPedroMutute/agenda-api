<?php

namespace App\Swagger\Requests;

/**
 * @OA\RequestBody(
 *     request="StoreReminderRequest",
 *     required=true,
 *     description="Dados para criar um novo lembrete",
 *     @OA\JsonContent(
 *         required={"appointment_id", "remind_at", "method"},
 *         @OA\Property(property="appointment_id", type="integer", example=5),
 *         @OA\Property(property="remind_at", type="string", format="date-time", example="2025-07-15T09:00:00Z"),
 *         @OA\Property(property="method", type="string", enum={"email", "message", "notification"}, example="email")
 *     )
 * )
 */
class StoreReminderRequestDoc {}
