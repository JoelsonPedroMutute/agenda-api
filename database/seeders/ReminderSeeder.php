<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reminder;
use App\Models\Appointment;

class ReminderSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::inRandomOrder()->take(10)->get();

        $appointments->each(function ($appointment) {
            Reminder::factory()->count(3)->create([
                'appointment_id' => $appointment->id,
            ]);
        });
    }
}