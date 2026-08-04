<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.pages.home', [
            'blogPosts' => $blogPosts,
        ]);
    }
}
