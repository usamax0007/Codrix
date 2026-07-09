@php $pageKey = 'portfolio'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.portfolio.title'))
@section('meta_description', config('xcodrix.pages.portfolio.description'))
@section('canonical', config('xcodrix.domain') . '/portfolio')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Portfolio</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Case Studies & <span class="xc-gradient-text">Projects</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Real solutions built for SaaS startups, healthcare, FinTech, and enterprise clients.</p>
    </div>
</section>

@include('frontend.sections.portfolio', ['preview' => false])

@include('frontend.components.cta-banner')
@endsection
