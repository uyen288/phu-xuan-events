<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,     // 1. Tạo danh mục trước
            UserSeeder::class,         // 2. Tạo danh sách tài khoản
            EventSeeder::class,        // 3. Tạo các sự kiện mẫu gắn danh mục & user
            RegistrationSeeder::class, // 4. Đổ dữ liệu đăng ký tham gia vào sau cùng
        ]);
    }
}