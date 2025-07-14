<?php

namespace App\Swagger\Controllers;

/**
 * @OA\Tag(
 *     name="Usuário",
 *     description="Operações relacionadas ao usuário autenticado e administrador"
 * )
 */
class UserControllerDocs
{
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     tags={"Usuário"},
     *     summary="Retorna os dados do usuário autenticado",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado",
     *         @OA\JsonContent(ref="#/components/schemas/UserWithRelations")
     *     )
     * )
     */
    public function show() {}

    /**
     * @OA\Post(
     *     path="/api/v1/user",
     *     tags={"Usuário"},
     *     summary="Registrar novo usuário",
     *     description="Cria um novo usuário com os dados fornecidos.",
     *     @OA\RequestBody(ref="#/components/requestBodies/StoreUserRequest"),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação nos dados fornecidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O campo email já está em uso."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register() {}

    /**
     * @OA\Put(
     *     path="/api/v1/user",
     *     tags={"Usuário"},
     *     summary="Atualiza dados pessoais do usuário autenticado",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateUserRequest"),
     *     @OA\Response(response=200, description="Dados atualizados", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function update() {}

    /**
     * @OA\Put(
     *     path="/api/v1/user/password",
     *     tags={"Usuário"},
     *     summary="Alterar senha do usuário autenticado",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(ref="#/components/requestBodies/ChangePasswordRequest"),
     *     @OA\Response(response=200, description="Senha alterada com sucesso")
     * )
     */
    public function changePassword() {}

    /**
     * @OA\Delete(
     *     path="/api/v1/user",
     *     tags={"Usuário"},
     *     summary="Excluir a própria conta (soft delete)",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Response(response=204, description="Conta deletada com sucesso")
     * )
     */
    public function destroySelf() {}

    // Endpoints administrativos (mesma tag "Usuário")

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users",
     *     tags={"Usuário"},
     *     summary="Listar todos os usuários (apenas admin)",
     *     description="Utilize o parâmetro `with_relations=true` para incluir relacionamentos como appointments e reminders.",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(
     *         name="with_relations",
     *         in="query",
     *         description="Se true, inclui os relacionamentos appointments e reminders",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserWithRelations")
     *         )
     *     )
     * )
     */
    public function index() {}

    /**
     * @OA\Get(
     *     path="/api/v1/admin/users/{id}",
     *     tags={"Usuário"},
     *     summary="Obter detalhes de um usuário (admin)",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Parameter(
     *         name="with_relations",
     *         in="query",
     *         description="Se true, inclui os relacionamentos appointments e reminders",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/UserWithRelations")
     *     )
     * )
     */
    public function showById() {}

    /**
     * @OA\Post(
     *     path="/api/v1/admin/users",
     *     tags={"Usuário"},
     *     summary="Criar um novo usuário (admin)",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\RequestBody(ref="#/components/requestBodies/StoreUserRequest"),
     *     @OA\Response(response=201, description="Usuário criado", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function store() {}

    /**
     * @OA\Put(
     *     path="/api/v1/admin/users/{id}",
     *     tags={"Usuário"},
     *     summary="Atualizar usuário (admin)",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(ref="#/components/requestBodies/UpdateUserRequest"),
     *     @OA\Response(response=200, description="Usuário atualizado", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function updateById() {}

    /**
     * @OA\Patch(
     *     path="/api/v1/admin/users/{id}/restore",
     *     tags={"Usuário"},
     *     summary="Restaurar usuário deletado (admin)",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Usuário restaurado", @OA\JsonContent(ref="#/components/schemas/User"))
     * )
     */
    public function restore() {}

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/users/{id}",
     *     tags={"Usuário"},
     *     summary="Excluir usuário (admin)",
     *     security={{"bearerAuth":{}}}, 
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Usuário deletado com sucesso")
     * )
     */
    public function destroyById() {}
}
