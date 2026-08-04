<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Faq;
use App\Models\Industry;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TechnologyCategory;
use App\Models\Testimonial;
use App\Models\WhyChooseUs;

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

        $whyChooseUsItems = WhyChooseUs::query()
            ->active()
            ->ordered()
            ->get();

        $industries = Industry::query()
            ->active()
            ->ordered()
            ->get();

        $technologyCategories = TechnologyCategory::query()
            ->active()
            ->ordered()
            ->get();

        $testimonials = Testimonial::query()
            ->active()
            ->ordered()
            ->get();

        return view('frontend.pages.home', [
            'blogPosts' => $blogPosts,
            'services' => $services,
            'faqs' => $faqs,
            'portfolios' => $portfolios,
            'whyChooseUsItems' => $whyChooseUsItems,
            'industries' => $industries,
            'technologyCategories' => $technologyCategories,
            'testimonials' => $testimonials,
        ]);
    }
}
