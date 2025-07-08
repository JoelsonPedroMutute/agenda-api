<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * Modelo que representa a entidade de agendamento (Appointment).
 * Cada instância corresponde a um compromisso criado pelo usuário.
 */
class Appointment extends Model
{
    use HasFactory;
 /**
     * Campos que podem ser preenchidos em massa (mass assignment).
     * Importante para evitar falhas de segurança ao criar/atualizar registros via formulário ou API.
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'scheduled_at',
        'status',
    ];


    /**
     * Relacionamento: Um agendamento pertence a um único usuário.
     * Exemplo: $appointment->user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: Um agendamento pode ter vários lembretes (reminders).
     * Exemplo: $appointment->reminders
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Escopo local para aplicar filtros dinâmicos ao agendamento.
     * Permite usar filtros na controller assim:
     * Appointment::filter(new AppointmentFilter($request))->get();
     */
    public function scopeFilter($query, QueryFilter $filters)
    {
        return $filters->apply($query);
    }
}

