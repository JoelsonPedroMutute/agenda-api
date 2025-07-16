<?php

namespace App\Swagger\Controllers;

/**
 * @OA\Tag(
 *     name="Compromissos",
 *     description="Gerenciamento de compromissos (appointments)"
 * )
 */
class AppointmentControllerDocs
{
    /**
     * @OA\Get(
     *     path="/api/v1/appointments",
     *     tags={"Compromissos"},
     *     summary="Listar compromissos (com filtros e paginação)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="per_page", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="with_relations", in="query", required=false, @OA\Schema(type="boolean")),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de compromissos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="status", type="integer", example=200),
     *             @OA\Property(property="message", type="string", example="Lista de compromissos recuperada com sucesso."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="appointments", type="array",
     *                     @OA\Items(ref="#/components/schemas/AppointmentWithRelations")
     *                 ),
     *                 @OA\Property(property="pagination", type="object")
     *             )
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/api/v1/appointments",
     *     tags={"Compromissos"},
     *     summary="Criar um novo compromisso",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(ref="#/components/requestBodies/StoreAppointmentRequest"),
     *     @OA\Response(response=201, description="Compromisso criado com sucesso", @OA\JsonContent(ref="#/components/schemas/Appointment"))
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/api/v1/appointments/{id}",
     *     tags={"Compromissos"},
     *     summary="Exibir um compromisso específico",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Compromisso encontrado", @OA\JsonContent(ref="#/components/schemas/AppointmentWithRelations"))
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/api/v1/appointments/{id}",
     *     tags={"Compromissos"},
     *     summary="Atualizar um compromisso",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateAppointmentRequest"),
     *     @OA\Response(response=200, description="Compromisso atualizado", @OA\JsonContent(ref="#/components/schemas/Appointment"))
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/v1/appointments/{id}",
     *     tags={"Compromissos"},
     *     summary="Excluir um compromisso",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Compromisso excluído com sucesso")
     * )
     */
    public function destroy() {}
}
