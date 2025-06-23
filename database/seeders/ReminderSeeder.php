<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Reminder;
use App\Models\Appointment;

class ReminderSeeder extends Seeder
{
    public function run(): void
    {
        Reminder::factory()
            ->count(30)
            ->for(Appointment::inRandomOrder()->first())
            ->create();
    }
}