<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    public function index(){
        $services = Service::with('items')->get();
        return view('frontend.pages.services', compact('services'));
    }
}
