<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Tạo tài khoản BAN QUẢN TRỊ (ADMIN)
        User::create([
            'name' => 'Ban Quản Trị Admin',
            'email' => 'admin@phuxuan.edu.vn',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Tạo tài khoản BAN TỔ CHỨC (ORGANIZER)
        User::create([
            'name' => 'CLB Tin Học Phú Xuân',
            'email' => 'organizer@phuxuan.edu.vn',
            'password' => Hash::make('password'),
            'role' => 'organizer',
        ]);

        // 3. Tạo tài khoản SINH VIÊN (STUDENT)
        User::create([
            'name' => 'Lê Văn An',
            'email' => 'student@phuxuan.edu.vn',
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // 4. Tạo thêm một vài Sự kiện mẫu để bảng thống kê có dữ liệu hiển thị luôn
        \App\Models\Category::create([
            'name' => 'Hội thảo công nghệ',
            'slug' => 'hoi-thao-cong-nghe',
            'description' => 'Các sự kiện về IT và lập trình'
        ]);

        \App\Models\Event::create([
            'title' => 'Sự kiện Chào tân sinh viên Đại học Phú Xuân',
            'description' => 'Chào đón các bạn tân sinh viên khóa mới nhập học.',
            'location' => 'Hội trường lớn - Tòa nhà Gamma',
            'start_time' => now()->addDays(5),
            'end_time' => now()->addDays(5)->addHours(4),
            'capacity' => 150,
            'status' => 'published',
            'user_id' => 2, // Thuộc về tài khoản Organizer vừa tạo ở trên
            'category_id' => 1
        ]);
        \App\Models\Registration::create([
            'user_id' => 3,     // ID của Sinh viên Lê Văn An
            'event_id' => 1,    // ID của Sự kiện Chào tân sinh viên
            'status' => 'pending',
            'note' => 'Em đăng ký vị trí hàng ghế đầu ạ.',
            'created_at' => now(),
        ]);

        // Đơn số 2: Tạo thêm một sinh viên khác đăng ký để bảng sinh động
        $student2 = User::create([
            'name' => 'Nguyễn Thị Bình',
            'email' => 'binhnt@phuxuan.edu.vn',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role' => 'student',
        ]);

        \App\Models\Registration::create([
            'user_id' => $student2->id,
            'event_id' => 1,
            'status' => 'pending',
            'note' => 'Cho em hỏi sự kiện có chứng nhận tham gia không ạ?',
            'created_at' => now()->subHours(2),
        ]);
    }
}