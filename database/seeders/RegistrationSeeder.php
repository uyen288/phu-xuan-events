<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Event;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy danh sách Sinh viên và Sự kiện mẫu để làm dữ liệu kết nối
        $student1 = User::where('email', 'student1@pxu.edu.vn')->first();
        $student2 = User::where('email', 'student2@pxu.edu.vn')->first();
        $event = Event::first();

        if ($student1 && $event) {
            // ĐÃ SỬA: Thay 'approved' bằng 'confirmed' để khớp 100% với file Migration của bạn
            DB::table('registrations')->insert([
                'user_id' => $student1->id,
                'event_id' => $event->id,
                'status' => 'confirmed', // Trạng thái đã phê duyệt/xác nhận tham gia
                'note' => 'Sinh viên năm cuối khoa CNTT',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($student2 && $event) {
            // Giữ nguyên 'pending' vì trong Migration của bạn đã có sẵn từ này
            DB::table('registrations')->insert([
                'user_id' => $student2->id,
                'event_id' => $event->id,
                'status' => 'pending', // Trạng thái chờ duyệt
                'note' => 'Đăng ký tham gia câu lạc bộ',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}