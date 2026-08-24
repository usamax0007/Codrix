<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\AddProjectController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnifiedLoginController;

Route::get('/', [HomeController::class, 'index']);

Route::get('/about', [PageController::class, 'about']);
Route::get('/services', [PageController::class, 'services']);
Route::get('/why-choose-us', [PageController::class, 'whyChooseUs']);
Route::get('/process', [PageController::class, 'process']);
Route::get('/industries', [PageController::class, 'industries']);
Route::get('/portfolio', [PageController::class, 'portfolio']);
Route::get('/technologies', [PageController::class, 'technologies']);
Route::get('/testimonials', [PageController::class, 'testimonials']);
Route::get('/faq', [PageController::class, 'faq']);
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::redirect('/team', '/about', 301);

// Unified Login Routes
Route::get('/login', [UnifiedLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [UnifiedLoginController::class, 'login'])->name('login.post');
Route::post('/logout', [UnifiedLoginController::class, 'logout'])->name('logout');

// Legacy Routes (redirect to unified login)
Route::redirect('/user/login', '/login', 301);
Route::redirect('/admin/login', '/login', 301);

Route::get('/user/dashboard', [UserAuthController::class, 'dashboard'])->name('user.dashboard')->middleware('auth');

// Admin Routes (Super Admin & Admin only)
Route::get('/admin/dashboard', [AdminAuthController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth');


Route::prefix('user/task-status')->name('user.task-status.')->middleware('auth')->group(function () {
    Route::get('/', [TaskStatusController::class, 'index'])->name('index');
    Route::post('/', [TaskStatusController::class, 'store'])->name('store');
    Route::post('/update-positions', [TaskStatusController::class, 'updatePositions'])->name('update-positions');
    Route::delete('/{taskStatus}', [TaskStatusController::class, 'destroy'])->name('destroy');
});

Route::prefix('user/task')->name('user.task.')->middleware('auth')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
    Route::post('/{task}/comment', [TaskController::class, 'addComment'])->name('comment');
    Route::put('/{task}/comment/{comment}', [TaskController::class, 'updateComment'])->name('comment.update');
    Route::delete('/{task}/comment/{comment}', [TaskController::class, 'deleteComment'])->name('comment.delete');
    Route::post('/{task}/subtask', [TaskController::class, 'addSubtask'])->name('subtask');
    Route::put('/{task}/subtask/{subtask}', [TaskController::class, 'toggleSubtask'])->name('subtask.toggle');
    Route::delete('/{task}/subtask/{subtask}', [TaskController::class, 'deleteSubtask'])->name('subtask.delete');
    Route::put('/{task}/status', [TaskController::class, 'updateStatus'])->name('status.update');
});

Route::prefix('user/add-project')->name('user.add-project.')->middleware('auth')->group(function () {
    Route::get('/', [AddProjectController::class, 'index'])->name('index');
    Route::get('/create', [AddProjectController::class, 'create'])->name('create');
    Route::post('/', [AddProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [AddProjectController::class, 'show'])->name('show');
    Route::get('/{project}/edit', [AddProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [AddProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [AddProjectController::class, 'destroy'])->name('destroy');
});

Route::prefix('user/users')->name('user.users.')->middleware('auth')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/create', [UserManagementController::class, 'create'])->name('create');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('assign-role');
});

// Admin User Management Routes
Route::prefix('admin/users')->name('admin.users.')->middleware('auth')->group(function () {
    Route::get('/', [UserManagementController::class, 'index'])->name('index');
    Route::get('/create', [UserManagementController::class, 'create'])->name('create');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::get('/{user}/edit', [UserManagementController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
    Route::post('/{user}/assign-role', [UserManagementController::class, 'assignRole'])->name('assign-role');
});

// Admin Projects Routes
Route::prefix('admin/projects')->name('admin.projects.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AddProjectController::class, 'index'])->name('index');
    Route::get('/create', [AddProjectController::class, 'create'])->name('create');
    Route::post('/', [AddProjectController::class, 'store'])->name('store');
    Route::get('/{project}', [AddProjectController::class, 'show'])->name('show');
    Route::get('/{project}/edit', [AddProjectController::class, 'edit'])->name('edit');
    Route::put('/{project}', [AddProjectController::class, 'update'])->name('update');
    Route::delete('/{project}', [AddProjectController::class, 'destroy'])->name('destroy');
});

// Admin Tasks Routes
Route::prefix('admin/tasks')->name('admin.tasks.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
});

// Admin Task Status Routes
Route::prefix('admin/task-status')->name('admin.task-status.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [TaskStatusController::class, 'index'])->name('index');
    Route::post('/', [TaskStatusController::class, 'store'])->name('store');
    Route::delete('/{status}', [TaskStatusController::class, 'destroy'])->name('destroy');
    Route::post('/reorder', [TaskStatusController::class, 'reorder'])->name('reorder');
});

// Admin Attendance Routes (placeholder)
Route::prefix('admin/attendance')->name('admin.attendance.')->middleware('auth')->group(function () {
    Route::get('/', function() {
        return redirect()->route('admin.dashboard')->with('info', 'Attendance module coming soon');
    })->name('index');
});

// Admin Admins Routes (Super Admin only)
Route::prefix('admin/admins')->name('admin.admins.')->middleware('auth')->group(function () {
    Route::get('/', [UserManagementController::class, 'adminsIndex'])->name('index');
    Route::get('/create', [UserManagementController::class, 'adminsCreate'])->name('create');
    Route::post('/', [UserManagementController::class, 'adminsStore'])->name('store');
    Route::get('/{user}/edit', [UserManagementController::class, 'adminsEdit'])->name('edit');
    Route::put('/{user}', [UserManagementController::class, 'adminsUpdate'])->name('update');
    Route::delete('/{user}', [UserManagementController::class, 'adminsDestroy'])->name('destroy');
});

// Admin Reports Routes (Super Admin and Admin)
Route::prefix('admin/reports')->name('admin.reports.')->middleware('auth')->group(function () {
    Route::get('/', function() {
        return view('frontend.admin.reports.index');
    })->name('index');
});

// Admin Settings Routes (Super Admin only)
Route::prefix('admin/settings')->name('admin.settings.')->middleware('auth')->group(function () {
    Route::get('/', function() {
        return view('frontend.admin.settings.index');
    })->name('index');
});

// Admin Roles Routes (Super Admin only)
Route::prefix('admin/roles')->name('admin.roles.')->middleware('auth')->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index');
    Route::get('/create', [RoleController::class, 'create'])->name('create');
    Route::post('/', [RoleController::class, 'store'])->name('store');
    Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('edit');
    Route::put('/{role}', [RoleController::class, 'update'])->name('update');
    Route::delete('/{role}', [RoleController::class, 'destroy'])->name('destroy');
});

// User Profile Routes
Route::prefix('user/profile')->name('user.profile.')->middleware('auth')->group(function () {
    Route::get('/', function() {
        return view('frontend.user.profile.index');
    })->name('index');
});

// User Orders Routes
Route::prefix('user/orders')->name('user.orders.')->middleware('auth')->group(function () {
    Route::get('/', function() {
        return view('frontend.user.orders.index');
    })->name('index');
});
