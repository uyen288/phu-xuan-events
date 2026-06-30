<?php

namespace Database\Factories;

use App\Models\Registration;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::where('role', 'student')->inRandomOrder()->value('id'),
            'event_id' => Event::inRandomOrder()->value('id'),
            'status' => fake()->randomElement([
                'pending',
                'confirmed',
                'cancelled'
            ]),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
