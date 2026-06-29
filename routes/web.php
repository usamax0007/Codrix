<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HomeController;


Route::get('/', [HomeController::class, 'index']);

Route::get('/services', [ServiceController::class, 'index']);

Route::get('/about', function () { return view('frontend.pages.about-us'); });
Route::get('/portfolio', function () { return view('frontend.pages.portfolio'); });
Route::get('/team', function () { return view('frontend.pages.team'); });
Route::get('/why-choose-us', function () { return view('frontend.pages.why-choose-us'); });
Route::get('/testimonials', function () { return view('frontend.pages.testimonials'); });

Route::get('/contact', [ContactController::class, 'index']);
Route::post('/contact', [ContactController::class, 'store']);
