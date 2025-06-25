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
        // Usuário fixo para testes (email e senha conhecidos)
        $user = User::factory()->create([
            'name' => 'Joelson',
            'email' => 'joelson@example.com',
            'password' => bcrypt('password'), // senha = password
        ]);

        // Cria 3 appointments para esse usuário
        $appointments = Appointment::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        // Para cada appointment criado, cria 2 reminders
        foreach ($appointments as $appointment) {
            Reminder::factory()->count(2)->create([
                'appointment_id' => $appointment->id,
            ]);
        }

        // Cria mais 9 usuários genéricos
        User::factory()->count(9)->create();
    }
}
