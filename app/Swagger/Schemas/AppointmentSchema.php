<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Appointment",
 *     type="object",
 *     title="Appointment",
 *     description="Agendamento do usuário",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Consulta médica"),
 *     @OA\Property(property="datetime", type="string", format="date-time", example="2025-08-20T14:30:00Z"),
 *     @OA\Property(property="status", type="string", example="pendente"),
 *     @OA\Property(property="user_id", type="integer", example=5)
 * )
 */
class AppointmentSchema {}
