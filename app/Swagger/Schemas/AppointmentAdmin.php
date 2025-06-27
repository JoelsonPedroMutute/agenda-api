<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="AppointmentAdmin",
 *     type="object",
 *     title="Agendamento (Admin)",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Consulta Médica"),
 *     @OA\Property(property="description", type="string", example="Agendamento com o clínico geral."),
 *     @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
 *     @OA\Property(property="start_time", type="string", example="14:00"),
 *     @OA\Property(property="end_time", type="string", example="15:00"),
 *     @OA\Property(property="status", type="string", example="pendente"),
 *     @OA\Property(
 *         property="reminders",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/ReminderAdmin")
 *     ),
 *     @OA\Property(property="user", ref="#/components/schemas/UserAdmin")
 * )
 */
class AppointmentAdmin {}
