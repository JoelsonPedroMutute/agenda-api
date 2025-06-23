<?php

namespace App\Models;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

      public function reminders()
{
    return $this->hasMany(Reminder::class);
}


    // Filtro usando QueryFilter com Request
   public function scopeFilter($query, $filter)
{
    return $filter->apply($query);
}
}
