<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.pages.about');
    }

    public function services()
    {
        return view('frontend.pages.services', [
            'services' => config('xcodrix.services'),
        ]);
    }

    public function whyChooseUs()
    {
        return view('frontend.pages.why-choose-us');
    }

    public function process()
    {
        return view('frontend.pages.process');
    }

    public function industries()
    {
        return view('frontend.pages.industries');
    }

    public function portfolio()
    {
        return view('frontend.pages.portfolio');
    }

    public function technologies()
    {
        return view('frontend.pages.technologies');
    }

    public function testimonials()
    {
        return view('frontend.pages.testimonials');
    }

    public function faq()
    {
        return view('frontend.pages.faq');
    }

    public function blog()
    {
        return view('frontend.pages.blog');
    }
}
