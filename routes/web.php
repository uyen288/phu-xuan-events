<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

// public route
Route::get('/', function () {
    return view('welcome');
});
// Nhóm các Route yêu cầu bắt buộc phải ĐĂNG NHẬP (`auth`)
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Đồng bộ trang Dashboard mặc định của hệ thống đi qua Controller
    // Cách này giúp giao diện không bao giờ bị lỗi thiếu biến (Undefined variable) nữa
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // 2. Quản lý hồ sơ cá nhân
    Route::singleton('profile', ProfileController::class)->creatable();

    // 3. PHÂN HỆ CỦA THÀNH VIÊN B: Thống kê & Duyệt đơn & Xuất báo cáo
    Route::get('/admin/export-csv/{event_id}', [DashboardController::class, 'exportCSV'])->name('admin.export.csv');
    Route::patch('/admin/registrations/{id}/approve', [DashboardController::class, 'approve'])->name('admin.registrations.approve');
    Route::patch('/admin/registrations/{id}/reject', [DashboardController::class, 'reject'])->name('admin.registrations.reject');

    // 4. BỔ SUNG: Route xử lý đăng ký tham gia sự kiện dành cho Sinh viên (Người B)
    Route::post('/events/{event_id}/register', [DashboardController::class, 'registerEvent'])->name('events.register');

    // Quản lý các chức năng CRUD sự kiện
    Route::resource('events', EventController::class);
});

require __DIR__ . '/auth.php';

