<?php

use App\Http\Controllers\User\AttendanceController;
use App\Http\Controllers\User\Auth\LoginController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\ProjectController;
use App\Http\Controllers\User\SubtaskController;
use App\Http\Controllers\User\TaskController;
use App\Http\Controllers\User\TaskStatusController;
use App\Http\Controllers\User\UserController;
use App\Support\AppPermission;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::middleware(['auth', 'user'])->group(function (): void {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::post('logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('can:'.AppPermission::TASKS_ACCESS)->group(function (): void {
        Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('tasks/columns/{task_status}', [TaskController::class, 'column'])->name('tasks.columns');
        Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store')->middleware('can:'.AppPermission::TASKS_CREATE);;
        Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show');
        Route::patch('tasks/{task}/assignees', [TaskController::class, 'updateAssignees'])->name('tasks.assignees.update')->middleware('can:'.AppPermission::TASKS_ASSIGN);
        Route::post('tasks/{task}/comments', [TaskController::class, 'storeComment'])->name('tasks.comments.store');
        Route::patch('tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');
        Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy')->middleware('can:'.AppPermission::TASKS_DELETE);

        Route::post('tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('tasks.subtasks.store')->middleware('can:'.AppPermission::TASKS_CREATE_SUBTASK);
        Route::patch('tasks/{task}/subtasks/reorder', [SubtaskController::class, 'reorder'])->name('tasks.subtasks.reorder')->middleware('can:'.AppPermission::TASKS_CREATE_SUBTASK);
        Route::put('tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'update'])->name('tasks.subtasks.update')->middleware('can:'.AppPermission::TASKS_CREATE_SUBTASK);
        Route::patch('tasks/{task}/subtasks/{subtask}/toggle', [SubtaskController::class, 'toggle'])->name('tasks.subtasks.toggle');
        Route::delete('tasks/{task}/subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('tasks.subtasks.destroy')->middleware('can:'.AppPermission::TASKS_CREATE_SUBTASK);
    });

    Route::middleware('can:'.AppPermission::PROJECTS_ACCESS)->prefix('projects')->name('projects.')->group(function (): void {
        Route::get('/', [ProjectController::class, 'index'])->name('index');
        Route::get('{project}', [ProjectController::class, 'show'])->name('show');
        Route::post('/', [ProjectController::class, 'store'])->name('store')->middleware('can:'.AppPermission::PROJECTS_MANAGE);
        Route::put('{project}', [ProjectController::class, 'update'])->name('update')->middleware('can:'.AppPermission::PROJECTS_MANAGE);
        Route::delete('{project}', [ProjectController::class, 'destroy'])->name('destroy')->middleware('can:'.AppPermission::PROJECTS_MANAGE);
    });

    Route::middleware('can:'.AppPermission::TASKS_MANAGE_STATUSES)->prefix('task-statuses')->name('task-statuses.')->group(function (): void {
        Route::get('/', [TaskStatusController::class, 'index'])->name('index');
        Route::post('/', [TaskStatusController::class, 'store'])->name('store');
        Route::patch('reorder', [TaskStatusController::class, 'reorder'])->name('reorder');
        Route::put('{task_status}', [TaskStatusController::class, 'update'])->name('update');
        Route::patch('{task_status}/toggle', [TaskStatusController::class, 'toggle'])->name('toggle');
        Route::delete('{task_status}', [TaskStatusController::class, 'destroy'])->name('destroy');
    });

    Route::middleware('can:'.AppPermission::USERS_MANAGE)->prefix('users')->name('users.')->group(function (): void {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });

    Route::middleware('can:'.AppPermission::ATTENDANCE_ACCESS)->group(function (): void {
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::post('attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.check-in');
        Route::post('attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.check-out');
    });
});
