<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Filters\AppointmentFilter;
use Reminder;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    /**
     * Relacionamento: um agendamento pertence a um usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento: um agendamento pode ter muitos lembretes
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Escopo de filtro para AppointmentFilter
     */
    public function scopeFilter($query, $filters)
    {
        return (new AppointmentFilter($filters))->apply($query);
    }
}
