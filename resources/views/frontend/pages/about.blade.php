@php $pageKey = 'about'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.about.title'))
@section('meta_description', config('xcodrix.pages.about.description'))
@section('canonical', config('xcodrix.domain') . '/about')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">About Us</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">About <span class="xc-gradient-text">XCodrix</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">A premium software development agency with 12+ years of experience building products that matter.</p>
    </div>
</section>

@include('frontend.sections.about', ['preview' => false])

<section class="py-20 bg-xc-dark/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
            @foreach(config('xcodrix.stats') as $stat)
                <div class="text-center scroll-reveal">
                    <div class="stat-value mb-2">{{ $stat['value'] }}</div>
                    <p class="text-slate-400 text-sm">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="max-w-3xl mx-auto space-y-6 text-slate-400 leading-relaxed scroll-reveal">
            <h2 class="text-2xl font-bold text-white">Who We Help</h2>
            <p>XCodrix partners with SaaS startups launching their first product, mid-size companies scaling existing platforms, and enterprises modernizing legacy systems. We serve clients in healthcare, FinTech, e-commerce, education, logistics, and telecommunications.</p>

            <h2 class="text-2xl font-bold text-white pt-4">What We Do</h2>
            <p>We provide end-to-end software development — from discovery and UI/UX design to development, testing, deployment, and ongoing support. Our core expertise includes AI development, SaaS platforms, Laravel backends, Vue.js and Nuxt.js frontends, mobile apps, Twilio communication systems, CRM development, API design, and cloud DevOps.</p>

            <h2 class="text-2xl font-bold text-white pt-4">Our Mission</h2>
            <p>To help businesses transform ideas into scalable, high-quality software that drives measurable growth. We believe in transparent communication, realistic timelines, and building long-term partnerships with every client.</p>
        </div>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
