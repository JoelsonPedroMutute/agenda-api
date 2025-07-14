<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="Reminder",
 *     title="Reminder",
 *     description="Lembrete sem relacionamentos",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="appointment_id", type="integer", example=12),
 *     @OA\Property(property="remind_at", type="string", format="date-time", example="2025-07-15T09:00:00Z"),
 *     @OA\Property(property="method", type="string", enum={"email", "message", "notification"}, example="email"),
 *     @OA\Property(property="message_status", type="string", nullable=true, example="sent"),
 *     @OA\Property(property="message_sid", type="string", nullable=true, example="SM123456789"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-07-10T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-07-10T13:45:00Z")
 * )
 *
 * @OA\Schema(
 *     schema="ReminderWithRelations",
 *     title="ReminderWithRelations",
 *     description="Lembrete com relacionamentos (compromisso e usuário)",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Reminder"),
 *         @OA\Schema(
 *             type="object",
 *             @OA\Property(
 *                 property="appointment",
 *                 type="object",
 *                 @OA\Property(property="id", type="integer", example=12),
 *                 @OA\Property(property="title", type="string", example="Consulta médica"),
 *                 @OA\Property(property="date", type="string", example="2025-07-15"),
 *                 @OA\Property(property="start_time", type="string", example="09:00"),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     @OA\Property(property="id", type="integer", example=4),
 *                     @OA\Property(property="name", type="string", example="Joana Silva"),
 *                     @OA\Property(property="email", type="string", example="joana@email.com")
 *                 )
 *             )
 *         )
 *     }
 * )
 */
class ReminderSchema {}
