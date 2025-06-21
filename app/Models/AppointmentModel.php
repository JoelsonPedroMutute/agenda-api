<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'date',
        'start_time',
        'end_time',
        'satus',

    ];
      public function user()
    {
        return $this->belongsTo(User::class);
    }
}
