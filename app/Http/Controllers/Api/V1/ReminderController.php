<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Services\ReminderService;
use App\Filters\ReminderFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Reminders",
 *     description="Operações relacionadas a lembretes"
 * )
 */
class ReminderController extends Controller
{
    protected $service;

    public function __construct(ReminderService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reminders",
     *     summary="Listar todos os lembretes do usuário autenticado",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Quantidade de lembretes por página",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de lembretes",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ReminderResource"))
     *     )
     * )
     */
    public function index(Request $request, ReminderFilter $filter)
    {
        $reminders = $this->service->getAll(Auth::id(), $filter, $request->per_page ?? 10);
        return ReminderResource::collection($reminders);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reminders",
     *     summary="Criar um novo lembrete",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreReminderRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lembrete criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderResource")
     *     )
     * )
     */
    public function store(StoreReminderRequest $request)
    {
        $reminder = $this->service->create(Auth::id(), $request->validated());
        return new ReminderResource($reminder);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reminders/{id}",
     *     summary="Visualizar um lembrete específico",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do lembrete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes do lembrete",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderResource")
     *     )
     * )
     */
    public function show($id)
    {
        $reminder = $this->service->find(Auth::id(), $id);
        return new ReminderResource($reminder);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reminders/{id}",
     *     summary="Atualizar um lembrete existente",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do lembrete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreReminderRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lembrete atualizado",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderResource")
     *     )
     * )
     */
    public function update(UpdateReminderRequest $request, $id)
    {
        $reminder = $this->service->update(Auth::id(), (int) $id, $request->validated());
        return new ReminderResource($reminder);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reminders/{id}",
     *     summary="Remover um lembrete",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do lembrete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Lembrete excluído com sucesso"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $this->service->delete(Auth::id(), $id);
        return response()->json([
            'Lembrete excluído com sucesso'
        ], 204);
    }
}
