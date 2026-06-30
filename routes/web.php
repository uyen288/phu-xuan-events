<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Khách vãng lai và Thành viên đều xem được)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

// M2.1: Danh sách sự kiện (public)
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// M2.2: Chi tiết sự kiện (public - chỉ xem published)
Route::get('/events/{event}', [EventController::class, 'show'])
    ->name('events.show')
    ->where('event', '[0-9]+');


/*
|--------------------------------------------------------------------------
| Authenticated Routes (Yêu cầu ĐĂNG NHẬP)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // M1.3: Hồ sơ cá nhân
    Route::singleton('profile', ProfileController::class)->creatable();

    // M3.5: Trang "Sự kiện của tôi" (Student)
    Route::get('/my-events', [RegistrationController::class, 'myEvents'])->name('my-events');

    // M3.1: Đăng ký tham gia sự kiện
    Route::post('/events/{eventId}/register', [RegistrationController::class, 'store'])
        ->name('registrations.store');

    // M3.2: Hủy đăng ký
    Route::delete('/events/{eventId}/cancel', [RegistrationController::class, 'cancel'])
        ->name('registrations.cancel');

    // M3.3: Organizer xem danh sách người đăng ký sự kiện của mình
    Route::get('/events/{event}/registrations', [RegistrationController::class, 'eventRegistrations'])
        ->name('events.registrations');

    /*
    |--------------------------------------------------------------------------
    | Organizer & Admin Routes (Tạo/Sửa sự kiện)
    |--------------------------------------------------------------------------
    */
    // Loại trừ index, show (public), destroy (admin only)
    Route::resource('events', EventController::class)->except(['index', 'show', 'destroy']);


    /*
    |--------------------------------------------------------------------------
    | Admin Routes (CHỈ Admin)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['admin'])->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

        // M2.3: Admin xóa sự kiện (soft delete)
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

        // M3.4: Phê duyệt / Từ chối đăng ký
        Route::patch('/admin/registrations/{id}/approve', [DashboardController::class, 'approve'])
            ->name('admin.registrations.approve');
        Route::patch('/admin/registrations/{id}/reject', [DashboardController::class, 'reject'])
            ->name('admin.registrations.reject');

        // M5.2: Export CSV
        Route::get('/admin/export-csv/{event_id}', [DashboardController::class, 'exportCSV'])
            ->name('admin.export.csv');

        // M1.4: Quản lý tài khoản user
        Route::resource('admin/users', UserController::class)->names([
            'index'   => 'admin.users.index',
            'create'  => 'admin.users.create',
            'store'   => 'admin.users.store',
            'edit'    => 'admin.users.edit',
            'update'  => 'admin.users.update',
            'destroy' => 'admin.users.destroy',
        ]);
    });
});

require __DIR__ . '/auth.php';
