<?php

namespace App\Filters;

class ReminderFilter extends QueryFilter
{
  
    public function method($value)
    {
        $this->builder->where('method', $value);
    }
    public function remind_at($value)
    {
        $this->builder->whereDate('remind_at', $value);
    }
    public function appointment_id($value)
    {
        $this->builder->where('appointment_id',$value);
       }
}
    