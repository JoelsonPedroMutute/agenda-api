<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Filters\ReminderFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reminder extends Model
{
  use HasFactory;

   protected $fillable = [
        'appointment_id',
        'remind_at',
        'method',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
     public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }
}
