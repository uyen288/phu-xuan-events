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

        // Lấy danh sách sự kiện
        $events = Event::all();

        // Lấy danh sách toàn bộ đơn đăng ký (Kèm user và event)
        $registrationsList = Registration::with(['user', 'event'])->orderBy('created_at', 'desc')->get();

        return view('dashboard', compact(
            'totalStudents',
            'totalRegistrations',
            'pendingRegistrations',
            'confirmedRegistrations',
            'events',
            'registrationsList'
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
}