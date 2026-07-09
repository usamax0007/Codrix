@php $pageKey = 'industries'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.industries.title'))
@section('meta_description', config('xcodrix.pages.industries.description'))
@section('canonical', config('xcodrix.domain') . '/industries')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Industries</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Industries We <span class="xc-gradient-text">Serve</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Deep domain expertise across SaaS, healthcare, FinTech, e-commerce, and more.</p>
    </div>
</section>

<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach(config('xcodrix.industries') as $industry)
                <article class="xc-card scroll-reveal">
                    <div class="w-12 h-12 rounded-xl bg-xc-cyan/10 flex items-center justify-center text-xc-cyan mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-white mb-2">{{ $industry['name'] }}</h2>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $industry['description'] }}</p>
                </article>
            @endforeach
        </div>

        <div class="max-w-3xl mx-auto mt-16 space-y-4 text-slate-400 leading-relaxed scroll-reveal">
            <h2 class="text-2xl font-bold text-white">Which Industries Does XCodrix Serve?</h2>
            <p>XCodrix works with companies across eight major industries. We understand the compliance requirements, user expectations, and technical challenges unique to each sector — whether that's HIPAA for healthcare, PCI for FinTech, or real-time communication for telecommunications.</p>
        </div>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
