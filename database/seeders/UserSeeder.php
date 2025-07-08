<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Appointment; // ✅ IMPORTAÇÃO NECESSÁRIA
use App\Models\Reminder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'joelson@example.com'],
            [
                'name'     => 'Joelson',
                'password' => bcrypt('password'),
                'role'     => 'user',
                'phone_number' => '+244976173722',
            ]
        );

        $appointments = Appointment::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $appointments->each(function ($appointment) {
            Reminder::factory()->count(2)->create([
                'appointment_id' => $appointment->id,
            ]);
        });

        User::factory(9)->create();
    }
}
