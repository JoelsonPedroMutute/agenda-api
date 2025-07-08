<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo que representa um lembrete associado a um agendamento.
 * Cada lembrete pertence a um agendamento e é acionado por data/hora.
 */
class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Define os campos que podem ser preenchidos automaticamente
     * ao criar ou atualizar lembretes.
     */
   protected $fillable = [
    'appointment_id',
    'remind_at',
    'method',
    'message_status',
    'message_sid',
];


    /**
     * Define como os atributos serão convertidos automaticamente.
     * Aqui, o campo 'remind_at' será sempre tratado como data/hora no formato especificado.
     */
    protected $casts = [
        'remind_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relacionamento: um lembrete pertence a um agendamento.
     * Exemplo de uso: $reminder->appointment
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Escopo para aplicar filtros à query de lembretes, usando ReminderFilter.
     * Exemplo de uso:
     * Reminder::filter(new ReminderFilter($request))->get();
     */
    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }
}
