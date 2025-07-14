<?php

namespace App\Swagger\Controllers;

/**
 * @OA\Tag(
 *     name="Lembretes",
 *     description="Gerenciamento de lembretes de compromissos"
 * )
 */
class ReminderControllerDocs
{
    /**
     * @OA\Get(
     *     path="/api/v1/reminders",
     *     summary="Listar lembretes (usuário autenticado ou admin)",
     *     tags={"Lembretes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="with_relations",
     *         in="query",
     *         description="Se true, carrega os relacionamentos (appointment e user)",
     *         required=false,
     *         @OA\Schema(type="boolean", default=true)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de lembretes",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ReminderWithRelations")
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Post(
     *     path="/api/v1/reminders",
     *     summary="Criar um novo lembrete",
     *     tags={"Lembretes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(ref="#/components/requestBodies/StoreReminderRequest"),
     *     @OA\Response(
     *         response=201,
     *         description="Lembrete criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderWithRelations")
     *     )
     * )
     */
    public function store() {}

    /**
     * @OA\Get(
     *     path="/api/v1/reminders/{id}",
     *     summary="Exibir um lembrete específico",
     *     tags={"Lembretes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lembrete encontrado", @OA\JsonContent(ref="#/components/schemas/ReminderWithRelations")),
     *     @OA\Response(response=404, description="Lembrete não encontrado")
     * )
     */
    public function show() {}

    /**
     * @OA\Put(
     *     path="/api/v1/reminders/{id}",
     *     summary="Atualizar um lembrete",
     *     tags={"Lembretes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateReminderRequest"),
     *     @OA\Response(response=200, description="Lembrete atualizado com sucesso", @OA\JsonContent(ref="#/components/schemas/ReminderWithRelations")),
     *     @OA\Response(response=404, description="Lembrete não encontrado")
     * )
     */
    public function update() {}

    /**
     * @OA\Delete(
     *     path="/api/v1/reminders/{id}",
     *     summary="Excluir um lembrete",
     *     tags={"Lembretes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Lembrete excluído com sucesso"),
     *     @OA\Response(response=404, description="Lembrete não encontrado")
     * )
     */
    public function destroy() {}
}
