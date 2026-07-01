<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\RegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
|
| Prefix: /api/v1 (cấu hình trong bootstrap/app.php hoặc RouteServiceProvider)
| Auth: Laravel Sanctum (stateless token)
|
*/

// ------------------------------------
// Public Routes (không cần auth)
// ------------------------------------

// M4.3: Đăng nhập, lấy token
Route::post('/auth/login', [AuthController::class, 'login']);

// M4.1: Danh sách sự kiện (có filter + search)
Route::get('/events', [EventController::class, 'index']);

// M4.2: Chi tiết sự kiện
Route::get('/events/{event}', [EventController::class, 'show']);

// ------------------------------------
// Protected Routes (yêu cầu Sanctum token)
// ------------------------------------
Route::middleware('auth:sanctum')->group(function () {

    // Đăng xuất
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    // M4.4: Đăng ký tham gia sự kiện
    Route::post('/registrations', [RegistrationController::class, 'store']);

    // M4.5: Xem danh sách đăng ký của tôi
    Route::get('/user/registrations', [RegistrationController::class, 'index']);
});

Route::prefix('v1')->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    Route::post('/events/{event}', [EventController::class, 'store']);
});
