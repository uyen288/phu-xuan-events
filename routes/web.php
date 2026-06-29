<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Public Routes (Dành cho tất cả mọi người, kể cả khách)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Yêu cầu đăng nhập - Cả Sinh viên và Admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Quản lý hồ sơ cá nhân
    Route::singleton('profile', ProfileController::class)->creatable();

    // 2. Chức năng đăng ký tham gia sự kiện dành cho Sinh viên
    Route::post('/events/{event_id}/register', [DashboardController::class, 'registerEvent'])->name('events.register');


    /*
    |--------------------------------------------------------------------------
    | Admin Routes (CHỈ Admin mới có quyền truy cập)
    |--------------------------------------------------------------------------
    | Thêm middleware 'admin' để bảo vệ các chức năng quản trị hệ thống
    */
    Route::middleware(['admin'])->group(function () {

        // Giao diện Dashboard quản trị tổng quan
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Các chức năng Quản lý: Duyệt đơn & Xuất báo cáo CSV
        Route::get('/admin/export-csv/{event_id}', [DashboardController::class, 'exportCSV'])->name('admin.export.csv');
        Route::patch('/admin/registrations/{id}/approve', [DashboardController::class, 'approve'])->name('admin.registrations.approve');
        Route::patch('/admin/registrations/{id}/reject', [DashboardController::class, 'reject'])->name('admin.registrations.reject');

        // SỬA: Chuyển ->except() ra phía sau ->resource()
        Route::resource('events', EventController::class)->except(['index', 'show']);
    });

    // SỬA: Chuyển ->only() ra phía sau ->resource() (Dành cho Sinh viên/Mọi người xem)
    Route::resource('events', EventController::class)->only(['index', 'show']);
});

require __DIR__ . '/auth.php';