<?php

namespace App\Swagger\Requests;

/**
 * @OA\RequestBody(
 *     request="UpdateAppointmentRequest",
 *     required=true,
 *     description="Dados para atualizar um compromisso existente",
 *     @OA\JsonContent(
 *         required={"title", "date", "start_time", "end_time"},
 *         @OA\Property(property="title", type="string", maxLength=255, example="Consulta com dermatologista"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Análise de pele com Dr. Ana"),
 *         @OA\Property(property="date", type="string", format="date", example="2025-08-01"),
 *         @OA\Property(property="start_time", type="string", example="09:00"),
 *         @OA\Property(property="end_time", type="string", example="10:00"),
 *         @OA\Property(
 *             property="status",
 *             type="string",
 *             nullable=true,
 *             enum={"ativo", "cancelado", "concluído"},
 *             example="concluído"
 *         )
 *     )
 * )
 */
class UpdateAppointmentRequestDocs {}
