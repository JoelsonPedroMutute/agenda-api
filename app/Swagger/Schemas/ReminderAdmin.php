<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="ReminderAdmin",
 *     type="object",
 *     title="Lembrete (Admin)",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="remind_at", type="string", format="date-time", example="2025-07-01T08:00:00Z"),
 *     @OA\Property(property="method", type="string", example="email"),
 *     @OA\Property(property="message", type="string", example="Não se esqueça da consulta médica."),
 *     @OA\Property(property="notification_time", type="string", example="2025-07-01T07:30:00Z"),
 *     @OA\Property(
 *         property="appointment",
 *         ref="#/components/schemas/AppointmentAdmin"
 *     )
 * )
 */
class ReminderAdmin {}
