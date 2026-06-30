<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use App\Models\Registration;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Lấy danh sách tất cả các sự kiện hiện có
        $events = Event::all();

        // 2. Lấy danh sách tất cả User có vai trò là sinh viên (student)
        $students = User::where('role', 'student')->get();

        // Nếu hệ thống chưa có sự kiện hoặc chưa có sinh viên thì dừng lại để tránh lỗi
        if ($events->isEmpty() || $students->isEmpty()) {
            return;
        }

        // 3. Duyệt qua từng sự kiện để tạo ngẫu nhiên đơn đăng ký
        foreach ($events as $event) {
            // Mỗi sự kiện sẽ có ngẫu nhiên từ 2 đến 7 sinh viên đăng ký tham gia
            $numberOfRegistrations = rand(2, 7);

            // Trộn ngẫu nhiên danh sách sinh viên và lấy ra số lượng cần thiết cho sự kiện này
            $randomStudents = $students->random(min($numberOfRegistrations, $students->count()));

            foreach ($randomStudents as $student) {
                Registration::create([
                    'event_id' => $event->id,
                    'user_id' => $student->id,

                    // ĐỒNG BỘ CHUẨN: Sử dụng bộ trạng thái phổ biến khớp tuyệt đối với DB của bạn
                    'status' => fake()->randomElement(['pending', 'confirmed', 'cancelled']),

                    'note' => fake()->randomElement([
                        'Em đăng ký tham gia ạ.',
                        'Mong muốn được học hỏi thêm kiến thức.',
                        'Em đăng ký suất vé VIP.',
                        null
                    ]),
                    'created_at' => fake()->dateTimeBetween('-1 week', 'now'),
                ]);
            }
        }
    }
}