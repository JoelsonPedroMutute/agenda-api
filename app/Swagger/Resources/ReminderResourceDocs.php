<?php

namespace App\Swagger\Resources;

/**
 * @OA\Schema(
 *     title="Reminder",
 *     description="Recurso de lembrete retornado pela API",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="appointment_id", type="integer", example=10),
 *     @OA\Property(property="remind_at", type="string", format="date-time", example="2025-08-10 09:00:00"),
 *     @OA\Property(property="method", type="string", example="sms", description="Método de notificação: email, sms ou notification"),
 *     @OA\Property(property="message_status", type="string", nullable=true, example="sent"),
 *     @OA\Property(property="message_sid", type="string", nullable=true, example="SMXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"),
 *
 *     @OA\Property(
 *         property="appointment",
 *         ref="#/components/schemas/Appointment",
 *         description="Dados do compromisso relacionado (se carregado com with('appointment'))"
 *     )
 * )
 */
class ReminderResourceDocs {}
