<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReminderResource;
use App\Models\Reminder;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/admin/reminders",
     *     summary="Listar todos os lembretes (admin)",
     *     tags={"Admin - Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de lembretes",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ReminderAdmin"))
     *         )
     *     )
     * )
     */
    public function index()
    {
        return ReminderResource::collection(
            Reminder::with('appointment.user')->paginate(10)
        );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/admin/reminders",
     *     summary="Criar lembrete (admin)",
     *     tags={"Admin - Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"appointment_id", "remind_at", "method"},
     *             @OA\Property(property="appointment_id", type="integer", example=1),
     *             @OA\Property(property="remind_at", type="string", format="date-time", example="2025-07-01T09:00:00"),
     *             @OA\Property(property="method", type="string", enum={"email", "sms", "notificação"}, example="email")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Lembrete criado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderAdmin")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'remind_at' => 'required|date',
            'method' => 'required|string|in:email,sms,notificação',
        ]);

        $reminder = Reminder::create($data);

        return response()->json([
            'message' => 'Reminder criado com sucesso.',
            'data' => new ReminderResource($reminder->load('appointment.user'))
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/admin/reminders/{id}",
     *     summary="Ver lembrete específico (admin)",
     *     tags={"Admin - Reminders"},
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
     *         description="Lembrete encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderAdmin")
     *     )
     * )
     */
    public function show(Reminder $reminder)
    {
        return new ReminderResource($reminder->load('appointment.user'));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/admin/reminders/{id}",
     *     summary="Atualizar lembrete (admin)",
     *     tags={"Admin - Reminders"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do lembrete",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Nova mensagem de lembrete"),
     *             @OA\Property(property="method", type="string", example="email"),
     *             @OA\Property(property="notification_time", type="string", format="date-time", example="2025-07-01T08:00:00")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lembrete atualizado com sucesso",
     *         @OA\JsonContent(ref="#/components/schemas/ReminderAdmin")
     *     )
     * )
     */
    public function update(Request $request, Reminder $reminder)
    {
        $reminder->update($request->only([
            'message', 'method', 'notification_time'
        ]));

        return new ReminderResource($reminder);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/admin/reminders/{id}",
     *     summary="Deletar lembrete (admin)",
     *     tags={"Admin - Reminders"},
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
     *         description="Lembrete deletado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Reminder deletado com sucesso")
     *         )
     *     )
     * )
     */
    public function destroy(Reminder $reminder)
    {
        $reminder->delete();

        return response()->json([
            'message' => 'Reminder deletado com sucesso'
        ]);
    }
}
