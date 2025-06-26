<?php

namespace App\Swagger\Schemas;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="StoreAppointmentRequest",
 *     type="object",
 *     required={"title", "description", "date", "start_time", "end_time"},
 *     @OA\Property(property="title", type="string", example="Reunião de planejamento"),
 *     @OA\Property(property="description", type="string", example="Reunião com a equipe para discutir o próximo sprint."),
 *     @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
 *     @OA\Property(property="start_time", type="string", format="time", example="09:00"),
 *     @OA\Property(property="end_time", type="string", format="time", example="10:30"),
 *     @OA\Property(property="status", type="string", example="pendente")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateAppointmentRequest",
 *     type="object",
 *     @OA\Property(property="title", type="string", example="Reunião atualizada"),
 *     @OA\Property(property="description", type="string", example="Descrição atualizada."),
 *     @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
 *     @OA\Property(property="start_time", type="string", format="time", example="14:00"),
 *     @OA\Property(property="end_time", type="string", format="time", example="15:30"),
 *     @OA\Property(property="status", type="string", example="concluído")
 * )
 *
 * @OA\Schema(
 *     schema="AppointmentResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="title", type="string", example="Reunião de planejamento"),
 *     @OA\Property(property="description", type="string", example="Reunião com a equipe"),
 *     @OA\Property(property="date", type="string", format="date", example="2025-07-01"),
 *     @OA\Property(property="start_time", type="string", format="time", example="09:00"),
 *     @OA\Property(property="end_time", type="string", format="time", example="10:00"),
 *     @OA\Property(property="status", type="string", example="pendente"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-25T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-25T10:00:00Z")
 * )
 */
class AppointmentSchemas {}
