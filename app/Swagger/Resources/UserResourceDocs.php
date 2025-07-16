<?php

namespace App\Swagger\Resources;

/**
 * @OA\Schema(
 *     title="User",
 *     description="Recurso de usuário autenticado ou relacionado a um agendamento",
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="name", type="string", example="Joelson Mutute"),
 *     @OA\Property(property="email", type="string", format="email", example="joelson@exemplo.com"),
 *     @OA\Property(property="role", type="string", example="admin", enum={"user", "admin"}),
 *     @OA\Property(property="phone_number", type="string", example="+244912345678"),
 *
 *     @OA\Property(
 *         property="appointments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Appointment"),
 *         description="Compromissos agendados, se carregados com with('appointments')"
 *     )
 * )
 */
class UserResourceDocs {}
