<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Http\Resources\ReminderResource;
use App\Services\ReminderService;
use App\Filters\ReminderFilter;
use App\Services\SmsService;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

/**
 * Controlador responsável por gerenciar lembretes (reminders) da aplicação.
 */
class ReminderController extends Controller
{
    protected ReminderService $service;
    protected SmsService $sms;

    /**
     * Aplica autenticação e injeta os serviços.
     */
    public function __construct(ReminderService $service, SmsService $sms)
    {
        $this->middleware('auth:sanctum');
        $this->service = $service;
        $this->sms = $sms;
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
     * Se o método for "sms", envia uma mensagem SMS com os dados do compromisso.
     */
    public function store(StoreReminderRequest $request)
    {
        $user = Auth::user();
        $reminder = $user->role === 'admin'
            ? $this->service->createAdmin($request->validated())
            : $this->service->create($user->id, $request->validated());
            $reminder->load('appointment.user');


        // Envio de SMS se o método for sms
if ($reminder->method === 'message') {
    $reminder->load('appointment.user');

    $appointment = $reminder->appointment;
    $user = $appointment->user;

    if ($user && $user->phone_number) {
        $hora = Carbon::parse($appointment->date . ' ' . $appointment->start_time)->format('H:i');
        $mensagem = "Lembrete: Você tem um compromisso agendado hoje às {$hora}. Não se atrase!";

        $smsResult = $this->sms->send($user->phone_number, $mensagem);

        \Log::info('Resultado do envio de SMS', ['smsResult' => $smsResult]);

        if ($smsResult && isset($smsResult['sid'], $smsResult['status'])) {
            $reminder->update([
                'message_status' => $smsResult['status'],
                'message_sid'    => $smsResult['sid'],
            ]);
            $reminder->refresh(); // <-- Atualiza os dados antes de retornar
        }
    } else {
        \Log::warning('Telefone do usuário não informado.', [
            'user_id' => $user->id ?? null,
            'appointment_id' => $appointment->id ?? null,
        ]);
    }
}





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

    /**
     * Método auxiliar privado para enviar um SMS com base no compromisso.
     * 
     * Busca o compromisso relacionado, formata o horário e envia uma mensagem para o número do usuário.
     */
    private function enviarSmsDoLembrete(int $appointmentId): void
    {
        $appointment = Appointment::with('user')->find($appointmentId);

        if (!$appointment || !$appointment->user) {
            return; // Compromisso ou usuário não encontrado
        }

        $hora = Carbon::parse($appointment->scheduled_at)->format('H:i');
        $mensagem = "Lembrete: Você tem um compromisso agendado hoje às {$hora}. Não se atrase!";

        $this->sms->send($appointment->user->phone_number, $mensagem);
    }
}
