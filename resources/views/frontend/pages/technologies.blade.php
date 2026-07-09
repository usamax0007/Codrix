@php $pageKey = 'technologies'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.technologies.title'))
@section('meta_description', config('xcodrix.pages.technologies.description'))
@section('canonical', config('xcodrix.domain') . '/technologies')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Technologies</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Technologies We <span class="xc-gradient-text">Use</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Modern, battle-tested technologies chosen for performance, security, and scalability.</p>
    </div>
</section>

@include('frontend.sections.technologies', ['preview' => false])

<section class="py-20">
    <div class="max-w-3xl mx-auto px-4 space-y-6 text-slate-400 leading-relaxed scroll-reveal">
        <h2 class="text-2xl font-bold text-white">What Technologies Does XCodrix Specialize In?</h2>
        <p>XCodrix specializes in the Laravel and PHP ecosystem for backends, Vue.js and Nuxt.js for frontends, React Native and Flutter for mobile, and Twilio for communication systems. We deploy on AWS and GCP with Docker, and integrate AI via OpenAI and Claude APIs.</p>
        <p>We choose technologies based on your project requirements — not trends. Every stack decision is documented and justified during the discovery phase.</p>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
