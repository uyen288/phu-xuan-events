<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// Trang chủ hệ thống công khai
Route::get('/', function () {
    return view('welcome');
});

// Trang dashboard mặc định của Laravel Breeze sau khi đăng nhập
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('dashboard');

// Nhóm các Route yêu cầu bắt buộc phải ĐĂNG NHẬP (`auth`)
Route::middleware('auth')->group(function () {

    // 1. Quản lý hồ sơ cá nhân
    Route::singleton('profile', ProfileController::class)->creatable();

    // 2. PHÂN HỆ CỦA THÀNH VIÊN B: Thống kê & Xuất báo cáo
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/export-csv/{event_id}', [DashboardController::class, 'exportCSV'])->name('admin.export.csv');
    Route::patch('/admin/registrations/{id}/approve', [DashboardController::class, 'approve'])->name('admin.registrations.approve');
    Route::patch('/admin/registrations/{id}/reject', [DashboardController::class, 'reject'])->name('admin.registrations.reject');

    // Quản lý events chung
    Route::resource('events', EventController::class);
});

require __DIR__ . '/auth.php';
