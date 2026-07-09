<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;

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
Route::get('/blog', [PageController::class, 'blog']);

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store']);

Route::get('/sitemap.xml', [SitemapController::class, 'index']);

Route::redirect('/team', '/about', 301);
