<?php
namespace App\Filters;

class AppointmentFilter extends QueryFilter
{
    public function title($value)
    {
       $this->builder->where('title', 'like', "%$value%");

    }

    public function date($value)
    {
        $this->builder->where('date', $value);
    }

    public function status($value)
    {
        $this->builder->where('status', $value);
    }
}