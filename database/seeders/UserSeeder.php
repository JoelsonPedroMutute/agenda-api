<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Reminder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Usuário fixo para testes
        $user = User::factory()->create([
            'name' => 'Joelson',
            'email' => 'joelson@example.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // 3 appointments para Joelson
        $appointments = Appointment::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        // 2 reminders por appointment
        $appointments->each(function ($appointment) {
            Reminder::factory()->count(2)->create([
                'appointment_id' => $appointment->id,
            ]);
        });

        // 9 usuários genéricos (role = 'user' por padrão na factory)
        User::factory(9)->create();
    }
}
