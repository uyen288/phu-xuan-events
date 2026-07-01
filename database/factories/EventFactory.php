<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('+1 day', '+1 month');
        $end = (clone $start)->modify('+3 hours');

        // 🎯 BẮT BUỘC PHẢI KHAI BÁO BIẾN NÀY TRƯỚC KHI GỌI NÓ Ở BÊN DƯỚI
        $localImages = [
            'image/slider1.jpg',
            'image/slider2.jpg',
            // Bạn có thể thêm đường dẫn ảnh khác vào đây nếu có nhé
        ];

        return [
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'location' => fake()->city(),
            'start_time' => $start,
            'end_time' => $end,
            'capacity' => fake()->numberBetween(30, 200),
            'status' => 'published',
            'banner' => fake()->randomElement($localImages),

            'user_id' => User::where('role', 'organizer')->inRandomOrder()->value('id'),
            'category_id' => Category::inRandomOrder()->value('id'),
        ];
    }
}