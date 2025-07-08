<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Trait para suportar soft delete
use App\Filters\QueryFilter;

/**
 * Modelo que representa a entidade de agendamento (Appointment).
 * Cada instância corresponde a um compromisso criado pelo usuário.
 */
class Appointment extends Model
{
    use HasFactory, SoftDeletes; 
    // HasFactory: permite uso de factories em testes/seeds
    // SoftDeletes: ativa o recurso de exclusão lógica (soft delete)

    /**
     * Campos que podem ser preenchidos em massa (mass assignment).
     * Importante para evitar falhas de segurança ao criar/atualizar registros via formulário ou API.
     */
    protected $fillable = [
        'user_id',       // ID do usuário dono do agendamento
        'title',         // Título do compromisso
        'description',   // Descrição (opcional)
        'date',          // Data do compromisso
        'start_time',    // Hora de início
        'end_time',      // Hora de término
        'scheduled_at',  // Data/hora em que o agendamento foi feito
        'status',        // Status: ativo, cancelado ou concluído
    ];

    /**
     * Relacionamento: Um agendamento pertence a um único usuário.
     * Exemplo de uso: $appointment->user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Um agendamento pode ter vários lembretes (reminders).
     * Exemplo de uso: $appointment->reminders
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Escopo local para aplicar filtros dinâmicos ao agendamento.
     * Permite aplicar filtros a uma query de forma flexível.
     * Exemplo de uso:
     *   Appointment::filter(new AppointmentFilter($request))->get();
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}
        