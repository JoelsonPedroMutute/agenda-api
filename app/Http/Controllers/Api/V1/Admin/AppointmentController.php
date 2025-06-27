<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/admin/appointments",
     *     summary="Listar todos os agendamentos (admin)",
     *     tags={"Admin - Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de agendamentos",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AppointmentAdmin"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        return AppointmentResource::collection(
            Appointment::with('user', 'reminders')->paginate(10)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/appointments",
     *     summary="Criar agendamento (admin)",
     *     tags={"Admin - Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"user_id", "title", "date", "start_time", "end_time"},
     *             @OA\Property(property="user_id", type="integer", example=2),
     *             @OA\Property(property="title", type="string", example="Consulta de rotina"),
     *             @OA\Property(property="description", type="string", example="Descrição opcional do agendamento"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-07-02"),
     *             @OA\Property(property="start_time", type="string", example="09:00"),
     *             @OA\Property(property="end_time", type="string", example="10:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Agendamento criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/AppointmentAdmin")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id'    => 'required|exists:users,id',
            'title'      => 'required|string|max:255',
            'description'=> 'nullable|string',
            'date'       => 'required|date',
            'start_time' => 'required',
            'end_time'   => 'required|after_or_equal:start_time',
        ]);

        $appointment = Appointment::create($data);

        return response()->json([
            'message' => 'Appointment created successfully.',
            'data' => new AppointmentResource($appointment)
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/appointments/{id}",
     *     summary="Ver agendamento específico (admin)",
     *     tags={"Admin - Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do agendamento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agendamento encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/AppointmentAdmin")
     *     )
     * )
     */
    public function show(Appointment $appointment)
    {
        return new AppointmentResource($appointment->load('user', 'reminders'));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/appointments/{id}",
     *     summary="Atualizar agendamento (admin)",
     *     tags={"Admin - Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do agendamento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string", example="Consulta médica atualizada"),
     *             @OA\Property(property="description", type="string", example="Descrição atualizada"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-07-02"),
     *             @OA\Property(property="start_time", type="string", example="10:00"),
     *             @OA\Property(property="end_time", type="string", example="11:00"),
     *             @OA\Property(property="status", type="string", example="concluído")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agendamento atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/AppointmentAdmin")
     *     )
     * )
     */
    public function update(Request $request, Appointment $appointment)
    {
        $appointment->update($request->only([
            'title', 'description', 'date', 'start_time', 'end_time', 'status'
        ]));

        return new AppointmentResource($appointment);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/appointments/{id}",
     *     summary="Deletar agendamento (admin)",
     *     tags={"Admin - Appointments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do agendamento",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Agendamento deletado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Appointment deletado com sucesso")
     *         )
     *     )
     * )
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return response()->json([
            'message' => 'Appointment deletado com sucesso'
        ]);
    }
}
