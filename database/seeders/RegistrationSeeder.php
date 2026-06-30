<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = \App\Models\User::where('role', 'student')->get();
        $events = \App\Models\Event::all();

        foreach ($students as $student) {
            $student->registeredEvents()->attach(
                $events->random(rand(1, 5))->pluck('id'),
                [
                    'status' => fake()->randomElement([
                        'pending',
                        'confirmed'
                    ]),
                    'note' => fake()->optional()->sentence(),
                ]
            );
        }
    }
}
