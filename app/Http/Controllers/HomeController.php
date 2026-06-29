<?php

namespace App\Http\Controllers;

use App\Models\HomeSetting;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(){
        $home = HomeSetting::first();

        if($home){
            $home = (object)[
                'title' => 'Accelerate Your Business Growth',
                'description' => 'Our powerful SaaS platform helps modern teams increase productivity by 40%.',
                'hero_image' => 'default.jpg',
                'btn_text' => 'Start Free Trial',
                'active_users_count' => '10,000+'
            ];
        }

        return view('frontend.pages.home', compact('home'));
    }
}
