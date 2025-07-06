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

/**
 * Controlador responsável por gerenciar lembretes (reminders) da aplicação.
 */
class ReminderController extends Controller
{
    protected ReminderService $service;

    /**
     * Aplica autenticação e injeta o serviço.
     */
    public function __construct(ReminderService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * Lista todos os lembretes com filtros e paginação.
     * Se o parâmetro `with_relations=false` for passado na query,
     * os relacionamentos não serão carregados (modo simplificado).
     */
    public function index(Request $request, ReminderFilter $filter)
    {
        $user = Auth::user();
        $perPage = $request->per_page ?? 10;
        $withRelations = filter_var($request->query('with_relations', 'true'), FILTER_VALIDATE_BOOLEAN);

        $reminders = $user->role === 'admin'
            ? $this->service->getAllAdmin($filter, $perPage, $withRelations)
            : $this->service->getAll($user->id, $filter, $perPage, $withRelations);

        return response()->json([
            'success' => true,
            'message' => 'Lista de lembretes recuperada com sucesso.',
            'data' => ReminderResource::collection($reminders),
        ], 200);
    }

    /**
     * Cria um novo lembrete.
     */
    public function store(StoreReminderRequest $request)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->createAdmin($request->validated())
            : $this->service->create($user->id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lembrete criado com sucesso.',
            'data' => new ReminderResource($reminder),
        ], 201);
    }

    /**
     * Exibe um lembrete específico.
     */
    public function show($id)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->findAdmin($id)
            : $this->service->find($user->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'Lembrete encontrado com sucesso.',
            'data' => new ReminderResource($reminder),
        ], 200);
    }

    /**
     * Atualiza os dados de um lembrete.
     */
    public function update(UpdateReminderRequest $request, $id)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->updateAdmin($id, $request->validated())
            : $this->service->update($user->id, $id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Lembrete atualizado com sucesso.',
            'data' => new ReminderResource($reminder),
        ], 200);
    }

    /**
     * Exclui um lembrete.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $user->role === 'admin'
            ? $this->service->deleteAdmin($id)
            : $this->service->delete($user->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'Lembrete excluído com sucesso.',
            'data' => null,
        ], 204);
    }
}
