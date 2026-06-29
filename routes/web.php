<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use Illuminate\Support\Facades\Route;

// public route
Route::get('/', function () {
    return view('welcome');
});

// need to login role admin route
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// need to login role any route
Route::middleware('auth')->group(function () {
    Route::resource('events', EventController::class);
    Route::singleton('profile', ProfileController::class)->creatable();
});

require __DIR__.'/auth.php';

// 

