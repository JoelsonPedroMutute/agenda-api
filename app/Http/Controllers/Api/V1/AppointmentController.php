<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use App\Filters\AppointmentFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

/**
 * Controlador responsável por gerenciar os compromissos (appointments) da API.
 * Os métodos são protegidos por autenticação Sanctum.
 * A lógica de negócio está separada no serviço AppointmentService.
 */
class AppointmentController extends Controller
{
    protected AppointmentService $service;

    /**
     * Injeta o serviço de compromissos e aplica o middleware de autenticação Sanctum.
     */
    public function __construct(AppointmentService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * Lista compromissos com paginação e filtros dinâmicos.
     * Admin vê todos os compromissos; usuários comuns apenas os próprios.
     * É possível incluir ou não os relacionamentos com ?with_relations=true|false
     */
    public function index(Request $request)
    {
        $filter = new AppointmentFilter($request);
        $perPage = $request->per_page ?? 10;
        $withRelations = $request->query('with_relations', 'true') === 'true';

        $appointments = Auth::user()->role === 'admin'
            ? $this->service->getAllAsAdmin($filter, $perPage, $withRelations)
            : $this->service->getAll(Auth::id(), $filter, $perPage, $withRelations);

        return Response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Lista de compromissos recuperada com sucesso.',
            'data' => [
                'appointments' => AppointmentResource::collection($appointments)->response()->getData(true)['data'],
                'pagination' => [
                    'current_page' => $appointments->currentPage(),
                    'per_page' => $appointments->perPage(),
                    'total' => $appointments->total(),
                    'from' => $appointments->firstItem(),
                    'to' => $appointments->lastItem(),
                    'last_page' => $appointments->lastPage(),
                    'links' => [
                        'first' => $appointments->url(1),
                        'last' => $appointments->url($appointments->lastPage()),
                        'prev' => $appointments->previousPageUrl(),
                        'next' => $appointments->nextPageUrl()
                    ]
                ]
            ]
        ], 200);
    }

    /**
     * Cria um novo compromisso.
     * Usuários comuns criam compromissos apenas para si.
     * Admin pode incluir o user_id no payload.
     */
    public function store(StoreAppointmentRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // Se não for admin, força o user_id com base no usuário autenticado
        if ($user->role !== 'admin') {
            $data['user_id'] = $user->id;
        }

        $appointment = $this->service->create($user->id, $data);

        return response()->json([
            'success' => true,
            'message' => 'Compromisso criado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }

    /**
     * Exibe um compromisso específico.
     * Admin pode visualizar qualquer compromisso; usuários apenas os próprios.
     */
    public function show($id)
    {
        $user = Auth::user();

        $appointment = $user->role === 'admin'
            ? $this->service->findAsAdmin($id)
            : $this->service->find($user->id, $id);

        return response()->json([
            'success' => true,
            'message' => 'Compromisso encontrado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ], 200);
    }

    /**
     * Atualiza um compromisso existente.
     * Admin pode atualizar qualquer compromisso; usuários apenas os próprios.
     */
    public function update(UpdateAppointmentRequest $request, $id)
    {
        $user = Auth::user();

        $appointment = $user->role === 'admin'
            ? $this->service->updateAsAdmin($id, $request->validated())
            : $this->service->update($user->id, $id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Compromisso atualizado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ], 200);
    }

    /**
     * Remove (soft delete) um compromisso.
     * Admin pode deletar qualquer compromisso; usuários apenas os próprios.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $this->service->deleteAsAdmin($id);
        } else {
            $this->service->delete($user->id, $id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Compromisso excluído com sucesso.',
            'data' => null,
        ], 204);
    }
}
