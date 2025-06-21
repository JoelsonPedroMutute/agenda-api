<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    public function scopeFilter($query, $filters)
{
    return (new \App\Filters\AppointmentFilter($filters))->apply($query);
}
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

}
