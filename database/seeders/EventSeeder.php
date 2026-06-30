<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Event::factory()->count(20)->create();

        foreach (\App\Models\Event::all() as $event) {
            $event->tags()->attach(
                \App\Models\Tag::inRandomOrder()
                    ->take(rand(2, 5))
                    ->pluck('id')
            );
        }
    }
}
