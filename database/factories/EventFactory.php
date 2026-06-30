<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array // <-- SỬA CHỖ NÀY THÀNH : array
    {
        $startTime = fake()->dateTimeBetween('now', '+2 months');
        $endTime = max($startTime, fake()->dateTimeInInterval($startTime, '+5 hours'));

        return [
            'user_id' => User::where('role', 'organizer')->inRandomOrder()->first()?->id ?? User::factory(),
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title' => fake()->sentence(6),
            'description' => fake()->paragraph(5),
            'location' => fake()->streetAddress() . ', Campus Phu Xuan',
            'banner' => null,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'capacity' => fake()->randomElement([30, 50, 100, 200, 500]),
            'status' => fake()->randomElement(['draft', 'published']),
        ];
    }
}