<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Executar os seeders específicos (que também usam factories internamente)
        $this->call([
            UserSeeder::class,
            AppointmentSeeder::class,
            ReminderSeeder::class,
        ]);

        // ✅ Criar um admin fixo para login de testes
        User::factory()->admin()->create([
            'name' => 'Admin Joelson',
            'email' => 'admin@example.com',
        ]);

        // ✅ Criar um usuário comum fixo
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
