<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="AppointmentWithRelations",
 *     title="Appointment (com relações)",
 *     description="Agendamento do usuário com dados relacionados opcionais",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/Appointment"),
 *         @OA\Schema(
 *             type="object",
 *             @OA\Property(
 *                 property="user",
 *                 ref="#/components/schemas/User",
 *                 nullable=true
 *             ),
 *             @OA\Property(
 *                 property="reminders",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Reminder"),
 *                 nullable=true
 *             )
 *         )
 *     }
 * )
 */
class AppointmentWithRelations {}
