<?php

namespace App\Swagger\Requests;

/**
 * @OA\RequestBody(
 *     request="UpdateReminderRequest",
 *     required=true,
 *     description="Dados para atualizar um lembrete (envie apenas os campos que deseja alterar)",
 *     @OA\JsonContent(
 *         @OA\Property(property="appointment_id", type="integer", example=3),
 *         @OA\Property(property="remind_at", type="string", format="date-time", example="2025-07-20T14:30:00Z"),
 *         @OA\Property(property="method", type="string", enum={"email", "message", "notification"}, example="notification")
 *     )
 * )
 */
class UpdateReminderRequestDoc {}
