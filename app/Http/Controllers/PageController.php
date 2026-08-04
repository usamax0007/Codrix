<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Industry;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\TechnologyCategory;
use App\Models\Testimonial;
use App\Models\WhyChooseUs;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function services()
    {
        return view('frontend.pages.services', [
            'services' => Service::query()->active()->ordered()->get(),
        ]);
    }

    public function whyChooseUs()
    {
        return view('frontend.pages.why-choose-us', [
            'whyChooseUsItems' => WhyChooseUs::query()->active()->ordered()->get(),
        ]);
    }

    public function process()
    {
        return view('frontend.pages.process');
    }

    public function industries()
    {
        return view('frontend.pages.industries', [
            'industries' => Industry::query()->active()->ordered()->get(),
        ]);
    }

    public function portfolio()
    {
        return view('frontend.pages.portfolio', [
            'portfolios' => Portfolio::query()->active()->ordered()->get(),
        ]);
    }

    public function technologies()
    {
        return view('frontend.pages.technologies', [
            'technologyCategories' => TechnologyCategory::query()->active()->ordered()->get(),
        ]);
    }

    public function testimonials()
    {
        return view('frontend.pages.testimonials', [
            'testimonials' => Testimonial::query()->active()->ordered()->get(),
        ]);
    }

    public function faq()
    {
        return view('frontend.pages.faq', [
            'faqs' => Faq::query()->active()->ordered()->get(),
        ]);
    }
}
