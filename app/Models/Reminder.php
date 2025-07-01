<?php

namespace App\Models;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'appointment_id',
        'remind_at',
        'method',
    ];

    protected $casts = [
        'remind_at' => 'datetime:Y-m-d H:i:s', // ✅ CAST ADICIONADO
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
