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
     * Display a listing of the resource.
     */
 public function index(Request $request, AppointmentFilter $filter)
{
    $appointments = $this->service->getAll(Auth::id(), $filter, $request->per_page ?? 10);

    return response()->json([
        'success' => true,
        'message' => 'Lista de compromissos recuperada com sucesso.',
        'data' => [
            'items' => AppointmentResource::collection($appointments)->response()->getData(true)['data'],
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
