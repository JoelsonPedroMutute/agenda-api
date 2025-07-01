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

    public function index(Request $request)
    {
        $filter = new AppointmentFilter($request); // ✅ Corrigido aqui
        $perPage = $request->per_page ?? 10;

        if (Auth::user()->role === 'admin') {
            $appointments = $this->service->getAllAsAdmin($filter, $perPage);
        } else {
            $appointments = $this->service->getAll(Auth::id(), $filter, $perPage);
        }

        return response()->json([
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
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        if ($user->role !== 'admin') {
            $data['user_id'] = $user->id;
        }

        $appointment = $this->service->create(Auth::id(), $data);

        return response()->json([
            'success' => true,
            'message' => 'Compromisso criado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ], 201);
    }

    public function show($id)
    {
        $appointment = $this->service->find(Auth::id(), $id);

        return response()->json([
            'success' => true,
            'message' => 'Compromisso encontrado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ]);
    }

    public function update(UpdateAppointmentRequest $request, $id)
    {
        $appointment = $this->service->update(Auth::id(), $id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Compromisso atualizado com sucesso.',
            'data' => new AppointmentResource($appointment),
        ]);
    }

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
