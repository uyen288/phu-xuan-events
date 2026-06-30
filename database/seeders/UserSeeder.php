<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo tài khoản Admin cố định để test quyền tối cao
        User::create([
            'name' => 'Quản Trị Viên',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // 2. Tạo tài khoản Organizer cố định để test giao diện quản lý sự kiện
        User::create([
            'name' => 'Ban Tổ Chức Sự Kiện',
            'email' => 'organizer@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'organizer',
            'email_verified_at' => now(),
        ]);

        // 3. Tạo tài khoản Student cố định để test bấm nút Đăng ký tham gia
        User::create([
            'name' => 'Sinh Viên Phú Xuân',
            'email' => 'student@gmail.com',
            'password' => bcrypt('12345678'),
            'role' => 'student',
            'email_verified_at' => now(),
        ]);

        // 4. Gọi Factory sinh thêm 30 tài khoản User ngẫu nhiên (gồm cả Student và Organizer)
        User::factory()->count(30)->create();
    }
}