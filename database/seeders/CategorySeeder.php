<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Học thuật & Khoa học', 'slug' => 'hoc-thuat-khoa-hoc'],
            ['name' => 'Hội thao - Thể dục', 'slug' => 'hoi-thao-the-duc'],
            ['name' => 'Tình nguyện xã hội', 'slug' => 'tinh-nguyen-xa-hoi'],
            ['name' => 'Văn nghệ - Giải trí', 'slug' => 'van-nghe-giai-tri'],
        ];

        DB::table('categories')->insert($categories);
    }
}