<?php

namespace Database\Factories;
use App\Models\Reminder;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reminder>
 */
class ReminderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
    'appointment_id' => Appointment::factory(), // Cria o agendamento associado
    'remind_at' => fake()->dateTimeBetween('now', '+1 week'),
    'method' => fake()->randomElement(['email', 'message', 'notification']),
        ];
    }
}
