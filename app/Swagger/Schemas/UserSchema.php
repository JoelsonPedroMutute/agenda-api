<?php

namespace App\Swagger\Schemas;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="Usuário",
 *     description="Informações básicas de um usuário",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="João Silva"),
 *     @OA\Property(property="email", type="string", example="joao@email.com"),
 *     @OA\Property(property="role", type="string", example="user"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-07-01T14:35:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-07-10T10:20:00Z")
 * )
 */
class UserSchema
{
}


/**
 * @OA\Schema(
 *     schema="UserWithRelations",
 *     title="Usuário com relacionamentos",
 *     description="Informações completas de um usuário, incluindo relacionamentos",
 *     allOf={
 *         @OA\Schema(ref="#/components/schemas/User"),
 *         @OA\Schema(
 *             type="object",
 *             @OA\Property(
 *                 property="appointments",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Appointment")
 *             ),
 *             @OA\Property(
 *                 property="reminders",
 *                 type="array",
 *                 @OA\Items(ref="#/components/schemas/Reminder")
 *             )
 *         )
 *     }
 * )
 */

class UserWithRelationsSchema
{
    
}
