<?php

namespace App\Filters;

class AppointmentFilter extends QueryFilter
{
    public function title($value)
    {
        return $this->builder->where('title', 'like', "%$value%");
    }

    public function date($value)
    {
        return $this->builder->where('date', $value);
    }

    public function status($value)
    {
        return $this->builder->where('status', $value);
    }
}
