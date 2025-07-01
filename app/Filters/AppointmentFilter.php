<?php

namespace App\Filters;

class AppointmentFilter extends QueryFilter
{
    /**
     * Filtra pelo título do compromisso (like).
     */
    public function title($value)
    {
        return $this->builder->where('title', 'like', '%' . trim($value) . '%');
    }

    /**
     * Filtra pela data exata.
     */
    public function date($value)
    {
        return $this->builder->whereDate('date', $value);
    }

    /**
     * Filtra pelo status: ativo, cancelado, concluido.
     */
    public function status($value)
    {
        $allowed = ['ativo', 'cancelado', 'concluido'];
        if (in_array($value, $allowed)) {
            return $this->builder->where('status', $value);
        }
        return $this->builder; // ignora filtro inválido
    }

    /**
     * (Opcional) Filtra por intervalo de datas.
     * Ex: ?start_date=2025-07-01&end_date=2025-07-10
     */
    public function start_date($value)
    {
        return $this->builder->whereDate('date', '>=', $value);
    }

    public function end_date($value)
    {
        return $this->builder->whereDate('date', '<=', $value);
    }
}
