<?php

namespace Database\Factories;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
    'user_id' => User::factory(), // Cria o usuário associado
    'title' => fake()->sentence(5),
    'description' => fake()->text(200),
    'date' => fake()->date(),
    'start_time' => fake()->time('H:i'),
    'end_time' => fake()->time('H:i'),
    'status' => fake()->randomElement(['ativo', 'cancelado', 'concluido']), // Adiciona status aleatório
        ];
    }
}
