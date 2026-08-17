<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskStatusController;

Route::get('/', [HomeController::class, 'index']);


Route::get('/user/dashboard', function () {
    return view('user.dashboard');
})->middleware('auth');


Route::get('/user/login', function () {
    return view('user.login');
})->name('login');

Route::post('/user/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    if (Auth::attempt($credentials, $request->remember)) {
        $request->session()->regenerate();
        return redirect()->intended('/user/dashboard');
    }

    return back()->withErrors([
        'email' => 'Provided credentials do not match our records.',
    ]);
});

Route::post('/user/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/user/login');
})->name('logout');

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



// Auth Protected Routes //
Route::middleware('auth')->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashboard');
    });

    // Projects Routes //
    Route::get('/user/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::post('/user/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::put('/user/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/user/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');

    // Tasks Routes //
    Route::get('/user/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('/user/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::post('/user/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::put('/user/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/user/comments', [CommentController::class, 'store']);
    Route::delete('/user/tasks/{id}', [TaskController::class, 'destroy']);
    Route::delete('/user/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Task Status Routes //
    Route::get('/user/task-statuses', [TaskStatusController::class, 'index'])->name('task-statuses.index');
});