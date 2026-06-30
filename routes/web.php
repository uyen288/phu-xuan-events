<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes (Khách vãng lai và Thành viên đều xem được)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// 💡 GỘP CHUNG: 'index' và 'show' công khai lên đầu để Laravel ưu tiên xử lý trước, 
// nhưng loại trừ tuyến '/events/create' để không bị nhận diện nhầm làm ID sự kiện.
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show')->where('event', '[0-9]+');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Yêu cầu ĐĂNG NHẬP nói chung)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Quản lý hồ sơ cá nhân sinh viên / giảng viên
    Route::singleton('profile', ProfileController::class)->creatable();

    // 2. M3.1: Chức năng bấm đăng ký tham gia sự kiện dành cho Sinh viên
    Route::post('/events/{event_id}/register', [DashboardController::class, 'registerEvent'])->name('events.register');

    /*
    |--------------------------------------------------------------------------
    | Organizer & Admin Routes (Yêu cầu quyền Tạo/Sửa sự kiện)
    |--------------------------------------------------------------------------
    */
    // Sử dụng resource loại trừ index, show, destroy để tránh ghi đè cấu hình phân quyền
    Route::resource('events', EventController::class)->except(['index', 'show', 'destroy']);


    /*
    |--------------------------------------------------------------------------
    | Admin Routes (CHỈ Quyền Admin tối cao)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {

        // M2.3: Chỉ Admin mới có quyền xóa hoàn toàn (Xóa mềm) sự kiện hệ thống
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

        // Giao diện Dashboard quản trị tổng quan
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Các chức năng Quản lý nâng cao: Duyệt đơn & Xuất báo cáo CSV (M3.4 & M5.2)
        Route::get('/admin/export-csv/{event_id}', [DashboardController::class, 'exportCSV'])->name('admin.export.csv');
        Route::patch('/admin/registrations/{id}/approve', [DashboardController::class, 'approve'])->name('admin.registrations.approve');
        Route::patch('/admin/registrations/{id}/reject', [DashboardController::class, 'reject'])->name('admin.registrations.reject');
    });
});

require __DIR__ . '/auth.php';
