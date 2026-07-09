@php $pageKey = 'why-choose-us'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.why-choose-us.title'))
@section('meta_description', config('xcodrix.pages.why-choose-us.description'))
@section('canonical', config('xcodrix.domain') . '/why-choose-us')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Why Choose Us</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Why Choose <span class="xc-gradient-text">XCodrix</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">85+ companies trust XCodrix to build their most important software products.</p>
    </div>
</section>

@include('frontend.sections.why-choose-us', ['preview' => false])

<section class="py-20 bg-xc-dark/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center scroll-reveal">
        <img src="{{ asset('images/why-choose-us.webp') }}" alt="Why choose XCodrix for software development" class="rounded-2xl w-full" width="560" height="400" loading="lazy">
        <div class="space-y-6">
            <h2 class="text-3xl font-bold text-white">A Partner, Not Just a Vendor</h2>
            <p class="text-slate-400 leading-relaxed">XCodrix takes ownership of your project's success. We proactively suggest improvements, flag risks early, and align our work with your business goals — not just your feature list.</p>
            <ul class="space-y-3">
                @foreach(['Direct access to senior developers', 'Weekly progress demos and transparent reporting', 'Fixed-price proposals with no hidden costs', 'Post-launch support and maintenance plans'] as $point)
                    <li class="flex items-center gap-3 text-slate-300">
                        <svg class="w-5 h-5 text-xc-cyan flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        {{ $point }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
