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

// User Authentication Routes
Route::get('/user/login', [UserAuthController::class, 'showLoginForm'])->name('user.login');
Route::post('/user/login', [UserAuthController::class, 'login'])->name('user.login.post');
Route::post('/user/logout', [UserAuthController::class, 'logout'])->name('user.logout');
Route::get('/user/dashboard', [UserAuthController::class, 'dashboard'])->name('user.dashboard')->middleware('auth');


Route::get('/user/task-status', [TaskStatusController::class, 'index'])->name('user.task-status')->middleware('auth');

Route::prefix('user/task')->name('user.task.')->middleware('auth')->group(function () {
    Route::get('/', [TaskController::class, 'index'])->name('index');
    Route::get('/create', [TaskController::class, 'create'])->name('create');
    Route::post('/', [TaskController::class, 'store'])->name('store');
    Route::get('/{task}', [TaskController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [TaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [TaskController::class, 'destroy'])->name('destroy');
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


