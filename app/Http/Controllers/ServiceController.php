<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        return view('frontend.pages.services', [
            'services' => Service::query()->active()->ordered()->get(),
        ]);
    }
}
