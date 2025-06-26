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

class AppointmentController extends Controller
{
    protected $service;

    public function __construct(AppointmentService $service)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/appointments",
     *     summary="Listar todos os compromissos paginados do usuário autenticado",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Quantidade de resultados por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de compromissos retornada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lista de compromissos recuperada com sucesso."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request, AppointmentFilter $filter)
    {
        $appointments = $this->service->getAll(Auth::id(), $filter, $request->per_page ?? 10);

        return response()->json([
            'success' => true,
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
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/appointments",
     *     summary="Criar um novo compromisso",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreAppointmentRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compromisso criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/AppointmentResource")
     *     )
     * )
     */
    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->service->create(Auth::id(), $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Compromisso criado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/appointments/{id}",
     *     summary="Exibir um compromisso específico",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do compromisso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compromisso encontrado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/AppointmentResource")
     *     )
     * )
     */
    public function show($id)
    {
        $appointment = $this->service->find(Auth::id(), $id);

        return response()->json([
            'success' => true,
            'message' => 'Compromisso encontrado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/appointments/{id}",
     *     summary="Atualizar um compromisso",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do compromisso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAppointmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compromisso atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/AppointmentResource")
     *     )
     * )
     */
    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointment = $this->service->update(Auth::id(), $id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Compromisso atualizado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/appointments/{id}",
     *     summary="Excluir um compromisso",
     *     tags={"Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do compromisso",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Compromisso excluído com sucesso"
     *     )
     * )
     */
    public function destroy($id)
    {
        $this->service->delete(Auth::id(), $id);

        return response()->json([
            'success' => true,
            'message' => 'Compromisso excluído com sucesso.',
            'data' => null,
        ], 204);
    }
}
