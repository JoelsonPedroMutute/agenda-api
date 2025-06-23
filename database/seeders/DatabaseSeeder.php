<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Executa os outros seeders
        $this->call([
            UserSeeder::class,
            AppointmentSeeder::class,
            ReminderSeeder::class,
        ]);

        // Cria um usuário fixo de teste
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
