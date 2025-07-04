<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            AppointmentSeeder::class,
            ReminderSeeder::class,
        ]);

        User::factory()->admin()->create([
            'name'  => 'Admin Joelson',
            'email' => 'admin@example.com',
        ]);

        User::factory()->create([
            'name'  => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
