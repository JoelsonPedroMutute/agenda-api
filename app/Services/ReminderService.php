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
     */
    public function create(int $userId, array $data): Reminder
    {
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
     * Recupera um lembrete do usuário autenticado.
     *
     * @param bool $fail Se true, lança exceção se não encontrado; se false, retorna null.
     */
    public function find(int $userId, int $id, bool $fail = true): ?Reminder
    {
        $query = Reminder::whereHas('appointment', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        });

        return $fail ? $query->findOrFail($id) : $query->find($id);
    }

    /**
     * Atualiza um lembrete do usuário autenticado.
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
     * [ADMIN] Recupera qualquer lembrete pelo ID.
     *
     * @param bool $fail Se true, lança exceção se não encontrado; se false, retorna null.
     */
    public function findAdmin(int $id, bool $fail = true): ?Reminder
    {
        return $fail
            ? Reminder::with('appointment.user')->findOrFail($id)
            : Reminder::with('appointment.user')->find($id);
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
     */
    private function enviarSms(Appointment $appointment, Reminder $reminder): void
    {
        $appointment->loadMissing('user');

        if (!$appointment->user || !$appointment->user->phone_number) {
            return;
        }

        $hora = Carbon::parse($appointment->scheduled_at)->format('H:i');
        $mensagem = "Lembrete: Você tem um compromisso agendado hoje às {$hora}. Não se atrase!";

        $response = $this->sms->send($appointment->user->phone_number, $mensagem);

        $reminder->update([
            'message_status' => $response['status'] ?? null,
            'message_sid'    => $response['sid'] ?? null,
        ]);
    }
}
