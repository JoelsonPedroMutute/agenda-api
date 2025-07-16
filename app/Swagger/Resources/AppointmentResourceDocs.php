<?php

namespace App\Swagger\Resources;

/**
 * @OA\Schema(
 *     title="Appointment",
 *     description="Recurso de compromisso retornado pela API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Consulta médica"),
 *     @OA\Property(property="description", type="string", example="Consulta com o Dr. João às 10h"),
 *     @OA\Property(property="date", type="string", format="date", example="2025-08-10"),
 *     @OA\Property(property="start_time", type="string", example="10:00"),
 *     @OA\Property(property="end_time", type="string", example="11:00"),
 *     @OA\Property(property="status", type="string", example="ativo"),
 *
 *     @OA\Property(
 *         property="user",
 *         ref="#/components/schemas/User",
 *         description="Usuário associado ao compromisso (se carregado com with('user'))"
 *     ),
 *
 *     @OA\Property(
 *         property="reminders",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Reminder"),
 *         description="Lista de lembretes associados (se carregado com with('reminders'))"
 *     )
 * )
 */
class AppointmentResourceDocs {}
