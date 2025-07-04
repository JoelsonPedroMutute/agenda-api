<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\User;


class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        Appointment::factory()->count(20)->create();
    }
}

