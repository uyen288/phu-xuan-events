<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo tài khoản siêu quản trị (Admin) - Quản lý user, phê duyệt đơn
        User::create([
            'name' => 'Admin Phu Xuan',
            'email' => 'admin@pxu.edu.vn',
            'password' => Hash::make('12345678'),
            'role' => 'admin',
        ]);

        // 2. Tạo tài khoản Người tổ chức (Organizer/Bán cán sự/Phòng ban) - Tạo sự kiện
        User::create([
            'name' => 'Doan Thanh Nien PXU',
            'email' => 'organizer@pxu.edu.vn',
            'password' => Hash::make('12345678'),
            'role' => 'organizer',
        ]);

        // 3. Tạo một vài tài khoản Sinh viên (Student) để test đăng ký sự kiện
        User::create([
            'name' => 'Le Van An',
            'email' => 'student1@pxu.edu.vn',
            'password' => Hash::make('12345678'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Nguyen Thi Binh',
            'email' => 'student2@pxu.edu.vn',
            'password' => Hash::make('12345678'),
            'role' => 'student',
        ]);
    }
}