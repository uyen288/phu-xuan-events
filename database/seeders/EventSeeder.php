<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy id của người tổ chức (Organizer) để gán vào khóa ngoại user_id
        $organizerId = User::where('role', 'organizer')->first()->id ?? 1;

        // Lấy id của các category tương ứng
        $catHocThuat = DB::table('categories')->where('slug', 'hoc-thuat-khoa-hoc')->first()->id ?? 1;
        $catTheThao = DB::table('categories')->where('slug', 'hoi-thao-the-duc')->first()->id ?? 2;

        // Sự kiện 1: Học thuật
        Event::create([
            'title' => 'Hội thảo khoa học Machine Learning và AI',
            'description' => 'Khám phá tiềm năng công nghệ trí tuệ nhân tạo thế hệ mới tại giảng đường đại học.',
            'location' => 'Hội trường lớn - Cơ sở 176 Hai Bà Trưng',
            'banner' => 'slider1.jpg',
            'start_time' => '2026-07-20 08:00:00',
            'end_time' => '2026-07-20 11:30:00',
            'capacity' => 150,
            'status' => 'published', // Đặt thành published để hiển thị ngay ra trang chủ công cộng
            'user_id' => $organizerId,
            'category_id' => $catHocThuat,
        ]);

        // Sự kiện 2: Thể thao
        Event::create([
            'title' => 'Giải bóng đá Nam sinh viên Phú Xuân 2026',
            'description' => 'Ngày hội thể thao sôi nổi chào mừng năm học mới với sự tham gia của tất cả các khoa.',
            'location' => 'Sân bóng đá cỏ nhân tạo cơ sở Phú Xuân',
            'banner' => 'slider2.jpg',
            'start_time' => '2026-08-15 15:30:00',
            'end_time' => '2026-08-20 17:30:00',
            'capacity' => 50,
            'status' => 'published',
            'user_id' => $organizerId,
            'category_id' => $catTheThao,
        ]);
    }
}