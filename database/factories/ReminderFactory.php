<?php

namespace Database\Factories;
use App\Models\Reminder;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;


class ReminderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'appointment_id' => Appointment::factory(),
            'remind_at'      => $this->faker->dateTimeBetween('now', '+1 week'),
            'method'         => $this->faker->randomElement(['email', 'message', 'notification']),
        ];
    }
}

