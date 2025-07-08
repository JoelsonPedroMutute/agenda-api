<?php

namespace App\Services;

use App\Models\Reminder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\User;
use App\Models\Appointment;
use App\Services\SmsService;
use Illuminate\Support\Carbon;

/**
 * Serviço responsável por lidar com toda a lógica de negócio
 * relacionada aos lembretes (Reminders), incluindo envio de SMS.
 */
class ReminderService
{
    protected SmsService $sms;

    /**
     * Injeta o serviço de SMS (Twilio).
     */
    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
    }

    /**
     * Recupera todos os lembretes pertencentes aos compromissos do usuário autenticado.
     *
     * @param int $userId ID do usuário autenticado.
     * @param mixed $filter Instância do filtro ReminderFilter.
     * @param int $perPage Paginação (itens por página).
     * @param bool $withRelations Define se carrega ou não os relacionamentos.
     * @return LengthAwarePaginator
     */
    public function getAll(int $userId, $filter, int $perPage = 10, bool $withRelations = true): LengthAwarePaginator
    {
        $query = Reminder::whereHas('appointment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        if ($withRelations) {
            $query->with('appointment');
        }

        return $query->filter($filter)->paginate($perPage);
    }

    /**
     * Cria um novo lembrete para um compromisso do usuário autenticado.
     * Se o método for "message", envia uma mensagem com os dados do compromisso.
     */
    public function create(int $userId, array $data): Reminder
    {
        // Busca o compromisso garantindo que pertence ao usuário autenticado
        $appointment = Appointment::where('id', $data['appointment_id'])
            ->where('user_id', $userId)
            ->firstOrFail();

        $reminder = $appointment->reminders()->create($data);

        if ($reminder->method === 'message') {
            $this->enviarSms($appointment, $reminder);
        }

        return $reminder;
    }

    /**
     * Recupera um lembrete por ID, desde que esteja vinculado a um compromisso do usuário autenticado.
     */
    public function find(int $userId, int $id): Reminder
    {
        return Reminder::whereHas('appointment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->findOrFail($id);
    }

    /**
     * Atualiza um lembrete pertencente ao usuário autenticado.
     */
    public function update(int $userId, int $id, array $data): Reminder
    {
        $reminder = $this->find($userId, $id);
        $reminder->update($data);

        return $reminder;
    }

    /**
     * Remove (soft delete) um lembrete do usuário autenticado.
     */
    public function delete(int $userId, int $id): void
    {
        $reminder = $this->find($userId, $id);
        $reminder->delete();
    }

    // ------------------------
    //  Ações exclusivas do ADMIN
    // ------------------------

    /**
     * [ADMIN] Lista todos os lembretes com filtros e paginação.
     */
    public function getAllAdmin($filter, int $perPage = 10, bool $withRelations = true): LengthAwarePaginator
    {
        $query = Reminder::query();

        if ($withRelations) {
            $query->with('appointment.user');
        }

        return $query->filter($filter)->paginate($perPage);
    }

    /**
     * [ADMIN] Cria um lembrete para qualquer compromisso existente.
     * Se o método for "message", envia uma mensagem com os dados do compromisso.
     */
    public function createAdmin(array $data): Reminder
    {
        $appointment = Appointment::findOrFail($data['appointment_id']);
        $reminder = $appointment->reminders()->create($data);

        if ($reminder->method === 'message') {
            $this->enviarSms($appointment, $reminder);
        }

        return $reminder;
    }

    /**
     * [ADMIN] Recupera qualquer lembrete pelo ID com relacionamentos.
     */
    public function findAdmin(int $id): Reminder
    {
        return Reminder::with('appointment.user')->findOrFail($id);
    }

    /**
     * [ADMIN] Atualiza qualquer lembrete.
     */
    public function updateAdmin(int $id, array $data): Reminder
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->update($data);

        return $reminder;
    }

    /**
     * [ADMIN] Remove qualquer lembrete (soft delete).
     */
    public function deleteAdmin(int $id): void
    {
        $reminder = Reminder::findOrFail($id);
        $reminder->delete();
    }

    /**
     * Envia um SMS com base no compromisso vinculado ao lembrete.
     *
     * @param Appointment $appointment Compromisso vinculado.
     * @param Reminder $reminder Lembrete recém-criado.
     */
    private function enviarSms(Appointment $appointment, Reminder $reminder): void
    {
        // Garante que o relacionamento 'user' está carregado
        $appointment->loadMissing('user');

        if (!$appointment->user || !$appointment->user->phone_number) {
            return;
        }

        $hora = Carbon::parse($appointment->scheduled_at)->format('H:i');
        $mensagem = "Lembrete: Você tem um compromisso agendado hoje às {$hora}. Não se atrase!";

        $response = $this->sms->send($appointment->user->phone_number, $mensagem);

        // Atualiza o lembrete com status e sid da Twilio
        $reminder->update([
            'message_status' => $response['status'] ?? null,
            'message_sid' => $response['sid'] ?? null,
        ]);
    }
}
