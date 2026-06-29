<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Registration;
use App\Models\Event; // Khai báo thêm Model Event để truy vấn sự kiện
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        // Đếm tổng số sinh viên, tổng số đơn đăng ký
        $totalStudents = User::where('role', 'student')->count();
        $totalRegistrations = Registration::count();

        // Đếm số đơn theo trạng thái để làm báo cáo
        $pendingRegistrations = Registration::where('status', 'pending')->count();
        $confirmedRegistrations = Registration::where('status', 'confirmed')->count();

        // ĐÃ SỬA: Thay Event::all() bằng truy vấn đếm số lượng đơn đã được duyệt (confirmed)
        $events = Event::withCount([
            'registrations' => function ($query) {
                $query->where('status', 'confirmed');
            }
        ])->get();

        // ĐỒNG BỘ: Đổi tên biến từ $registrationsList thành $registrations để khớp với file dashboard.blade.php
        $registrations = Registration::with(['user', 'event'])->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact(
            'totalStudents',
            'totalRegistrations',
            'pendingRegistrations',
            'confirmedRegistrations',
            'events',
            'registrations'
        ));
    }

    // Chức năng xuất danh sách sinh viên đăng ký của một sự kiện cụ thể ra file CSV (M5.2)
    public function exportCSV($eventId)
    {
        // Lấy ra sự kiện cùng danh sách đơn đăng ký và thông tin sinh viên đi kèm (Eager Loading)
        $event = Event::with('registrations.user')->findOrFail($eventId);
        $registrations = $event->registrations;

        $fileName = 'danh-sach-dang-ky-' . Str::slug($event->title) . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($registrations) {
            $file = fopen('php://output', 'w');

            // Xuất file UTF-8 cần có mã BOM để Excel không bị lỗi font chữ tiếng Việt
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Tiêu đề các cột trong file CSV
            fputcsv($file, ['STT', 'Tên Sinh Viên', 'Email', 'Ngày Đăng Ký', 'Trạng Thái']);

            foreach ($registrations as $key => $row) {
                // Kiểm tra xem đơn hàng có user hợp lệ không tránh lỗi logic
                if ($row->user) {
                    fputcsv($file, [
                        $key + 1,
                        $row->user->name,
                        $row->user->email,
                        $row->created_at->format('d/m/Y H:i'),
                        $row->status == 'confirmed' ? 'Đã duyệt' : ($row->status == 'pending' ? 'Chờ duyệt' : 'Đã hủy')
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Chức năng duyệt đơn đăng ký
    public function approve($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->status = 'confirmed';
        $registration->save();

        return redirect()->back()->with('success', 'Đã duyệt đơn đăng ký thành công.');
    }

    // Chức năng từ chối đơn đăng ký
    public function reject($id)
    {
        $registration = Registration::findOrFail($id);
        $registration->status = 'cancelled';
        $registration->save();

        return redirect()->back()->with('success', 'Đã từ chối đơn đăng ký.');
    }

    // BỔ SUNG: Chức năng xử lý khi Sinh viên bấm nút đăng ký tham gia sự kiện trực tiếp trên giao diện
    public function registerEvent(Request $request, $eventId)
    {
        $userId = auth()->id(); // Lấy ID của sinh viên đang đăng nhập
        $event = Event::with('registrations')->findOrFail($eventId);

        // 1. KIỂM TRA: Sinh viên đã đăng ký sự kiện này chưa?
        $alreadyRegistered = Registration::where('user_id', $userId)
            ->where('event_id', $eventId)
            ->exists();
        if ($alreadyRegistered) {
            return redirect()->back()->with('error', 'Bạn đã đăng ký tham gia sự kiện này từ trước rồi!');
        }

        // 2. KIỂM TRA: Sự kiện đã hết chỗ chưa?
        $confirmedCount = $event->registrations()->where('status', 'confirmed')->count();
        if ($confirmedCount >= $event->capacity) {
            return redirect()->back()->with('error', 'Rất tiếc, sự kiện này đã đầy chỗ!');
        }

        // 3. THỎA MÃN ĐIỀU KIỆN -> Tiến hành tạo đơn đăng ký mới ở trạng thái chờ duyệt (pending)
        Registration::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'status' => 'pending',
            'note' => $request->input('note'), // Lưu ghi chú từ form (nếu có)
        ]);

        return redirect()->back()->with('success', 'Đăng ký tham gia thành công! Vui lòng chờ Ban tổ chức phê duyệt.');
    }
}