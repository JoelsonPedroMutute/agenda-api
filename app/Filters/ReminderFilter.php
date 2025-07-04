<?php

namespace App\Filters;

class ReminderFilter extends QueryFilter
{

    /**
     * Filtra os lembretes pelo método de envio.
     * Exemplo de métodos: 'email', 'sms', 'notificação'
     * 
     * Exemplo de uso na URL:
     *   ?method=email
     */
    public function method($value)
    {
        $this->builder->where('method', $value);
    }

    /**
     * Filtra lembretes por data exata de agendamento.
     * A comparação é feita com a parte de data do campo `remind_at`.
     * 
     * Exemplo de uso na URL:
     *   ?remind_at=2025-07-04
     *  $value Data no formato YYYY-MM-DD.
     */
    public function remind_at($value)
    {
        $this->builder->whereDate('remind_at', $value);
    }

     /**
     * Filtra lembretes pelo ID do compromisso associado.
     * Este filtro retorna todos os lembretes relacionados a um determinado appointment.
     *
     * Exemplo de uso na URL:
     *   ?appointment_id=12
     * $value ID do compromisso.
     */
    public function appointment_id($value)
    {
        $this->builder->where('appointment_id',$value);
       }
}
    