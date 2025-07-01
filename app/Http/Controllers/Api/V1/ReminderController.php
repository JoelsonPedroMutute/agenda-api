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
    protected ReminderService $service;

    public function __construct(ReminderService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reminders",
     *     summary="Listar lembretes (baseado no perfil do usuário)",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="per_page", in="query", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lista de lembretes", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/ReminderResource")))
     * )
     */
    public function index(Request $request, ReminderFilter $filter)
    {
        $user = Auth::user();

        $reminders = $user->role === 'admin'
            ? $this->service->getAllAdmin($filter, $request->per_page ?? 10)
            : $this->service->getAll($user->id, $filter, $request->per_page ?? 10);

        return ReminderResource::collection($reminders);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/reminders",
     *     summary="Criar lembrete",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreReminderRequest")),
     *     @OA\Response(response=201, description="Lembrete criado", @OA\JsonContent(ref="#/components/schemas/ReminderResource"))
     * )
     */
    public function store(StoreReminderRequest $request)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->createAdmin($request->validated())
            : $this->service->create($user->id, $request->validated());

        return new ReminderResource($reminder);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/reminders/{id}",
     *     summary="Ver lembrete",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Detalhes do lembrete", @OA\JsonContent(ref="#/components/schemas/ReminderResource"))
     * )
     */
    public function show($id)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->findAdmin($id)
            : $this->service->find($user->id, $id);

        return new ReminderResource($reminder);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/reminders/{id}",
     *     summary="Atualizar lembrete",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UpdateReminderRequest")),
     *     @OA\Response(response=200, description="Lembrete atualizado", @OA\JsonContent(ref="#/components/schemas/ReminderResource"))
     * )
     */
    public function update(UpdateReminderRequest $request, $id)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->updateAdmin($id, $request->validated())
            : $this->service->update($user->id, $id, $request->validated());

        return new ReminderResource($reminder);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/reminders/{id}",
     *     summary="Excluir lembrete",
     *     tags={"Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Lembrete excluído", @OA\JsonContent(@OA\Property(property="message", type="string", example="Lembrete excluído com sucesso.")))
     * )
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $user->role === 'admin'
            ? $this->service->deleteAdmin($id)
            : $this->service->delete($user->id, $id);

        return response()->json(['message' => 'Lembrete excluído com sucesso.']);
    }
}
