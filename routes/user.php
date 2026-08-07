<?php

use App\Http\Controllers\User\AttendanceController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'user'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
    Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');
});
