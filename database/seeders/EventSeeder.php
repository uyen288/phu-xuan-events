<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo ra 50 sự kiện mẫu ngẫu nhiên
        Event::factory()->count(50)->create()->each(function ($event) {
            // Sau khi tạo xong mỗi sự kiện, gắn ngẫu nhiên từ 1 đến 3 thẻ (Tags) vào bảng trung gian
            $tags = Tag::inRandomOrder()->take(rand(1, 3))->pluck('id');
            $event->tags()->attach($tags);
        });
    }
}