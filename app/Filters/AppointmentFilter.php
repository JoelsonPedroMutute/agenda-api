<?php

namespace App\Filters;

class AppointmentFilter extends QueryFilter
{
    /**
     * Filtra os resultados pelo título do compromisso.
     * Realiza uma busca parcial com o operador SQL LIKE (case-insensitive).
     * 
     * Exemplo de uso na URL:
     *   ?title=reunião
     */
    public function title($value)
    {
        return $this->builder->where('title', 'like', '%' . trim($value) . '%');
    }

     /**
     * Filtra os resultados por uma data exata do compromisso.
     * 
     * Exemplo de uso na URL:
     *   ?date=2025-07-04
     */
    public function date($value)
    {
        return $this->builder->whereDate('date', $value);
    }

     /**
     * Filtra os compromissos de acordo com o status.
     * São aceitos apenas três valores: 'ativo', 'cancelado', 'concluido'.
     * Caso o valor não seja permitido, o filtro é ignorado.
     *
     * Exemplo de uso na URL:
     *   ?status=ativo
     */
    public function status($value)
    {
        $allowed = ['ativo', 'cancelado', 'concluido'];
        if (in_array($value, $allowed)) {
            return $this->builder->where('status', $value);
        }
        return $this->builder; // Ignora valores inválidos e retorna a consulta original
    }

     /**
     * (Opcional) Filtra compromissos a partir de uma data inicial.
     * 
     * Exemplo de uso na URL:
     *   ?start_date=2025-07-01
     */
    public function start_date($value)
    {
        return $this->builder->whereDate('date', '>=', $value);
    }

    
    /**
     * (Opcional) Filtra compromissos até uma data final.
     * 
     * Exemplo de uso na URL:
     * ?end_date=2025-07-10
     */
    public function end_date($value)
    {
        return $this->builder->whereDate('date', '<=', $value);
    }
}
