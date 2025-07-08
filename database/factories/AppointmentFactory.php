<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        // Gera uma data futura aleatória
        $date = $this->faker->dateTimeBetween('+1 day', '+1 month');

        return [
            'user_id'      => User::factory(),
            'title'        => $this->faker->sentence(5),
            'description'  => $this->faker->text(200),
            'date'         => $date->format('Y-m-d'),
            'start_time'   => $date->format('H:i:s'),
            'end_time'     => $date->modify('+1 hour')->format('H:i:s'),
            'scheduled_at' => $date->format('Y-m-d H:i:s'), // ✅ Incluído corretamente
            'status'       => $this->faker->randomElement(['ativo', 'cancelado', 'concluido']),
        ];
    }
}
