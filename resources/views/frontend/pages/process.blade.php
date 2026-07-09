@php $pageKey = 'process'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.process.title'))
@section('meta_description', config('xcodrix.pages.process.description'))
@section('canonical', config('xcodrix.domain') . '/process')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Development Process</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our Development <span class="xc-gradient-text">Process</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">A proven 6-step process that takes your idea from concept to production with full transparency.</p>
    </div>
</section>

@include('frontend.sections.process', ['preview' => false])

<section class="py-20">
    <div class="max-w-3xl mx-auto px-4 space-y-6 text-slate-400 leading-relaxed scroll-reveal">
        <h2 class="text-2xl font-bold text-white">How Does Our Development Process Work?</h2>
        <p>XCodrix follows an agile methodology tailored for software agencies. After an initial discovery call, we deliver a detailed proposal within 48 hours. Once approved, we move through design, development sprints, QA, and launch — with weekly demos so you always see real progress.</p>
        <p>Our process is designed for clarity: you know what's being built, when it ships, and how much it costs at every stage. No surprises, no scope creep without discussion.</p>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
