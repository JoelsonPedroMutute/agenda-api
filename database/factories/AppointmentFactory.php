<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'     => User::factory(),
            'title'       => $this->faker->sentence(5),
            'description' => $this->faker->text(200),
            'date'        => $this->faker->date(),
            'start_time'  => $this->faker->time('H:i'),
            'end_time'    => $this->faker->time('H:i'),
            'status'      => $this->faker->randomElement(['ativo', 'cancelado', 'concluido']),
        ];
    }
}

