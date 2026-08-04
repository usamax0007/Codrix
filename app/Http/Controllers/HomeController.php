<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Portfolio;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $blogPosts = BlogPost::query()
            ->published()
            ->latest('published_at')
            ->take(3)
            ->get();

        $services = Service::query()
            ->active()
            ->ordered()
            ->get();

        $faqs = Faq::query()
            ->active()
            ->ordered()
            ->get();

        $portfolios = Portfolio::query()
            ->active()
            ->ordered()
            ->get();

        return view('frontend.pages.home', [
            'blogPosts' => $blogPosts,
            'services' => $services,
            'faqs' => $faqs,
            'portfolios' => $portfolios,
        ]);
    }
}
