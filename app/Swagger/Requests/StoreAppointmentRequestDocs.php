<?php

namespace App\Swagger\Requests;

/**
 * @OA\RequestBody(
 *     request="StoreAppointmentRequest",
 *     required=true,
 *     description="Dados para criar um novo compromisso",
 *     @OA\JsonContent(
 *         required={"title", "date", "start_time", "end_time"},
 *         @OA\Property(property="title", type="string", maxLength=255, example="Consulta Médica"),
 *         @OA\Property(property="description", type="string", nullable=true, example="Consulta com o Dr. João"),
 *         @OA\Property(property="date", type="string", format="date", example="2025-07-20"),
 *         @OA\Property(property="start_time", type="string", example="14:30"),
 *         @OA\Property(property="end_time", type="string", example="15:30"),
 *         @OA\Property(
 *             property="status",
 *             type="string",
 *             nullable=true,
 *             enum={"ativo", "cancelado", "concluido"},
 *             example="ativo"
 *         )
 *     )
 * )
 */
class StoreAppointmentRequestDocs {}
