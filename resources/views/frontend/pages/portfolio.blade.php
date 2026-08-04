@php $pageKey = 'portfolio'; @endphp
@extends('frontend.layout.app')

@section('title', $portfolioSettings->meta_title ?: config('xcodrix.pages.portfolio.title'))
@section('meta_description', $portfolioSettings->meta_description ?: config('xcodrix.pages.portfolio.description'))
@section('canonical', config('xcodrix.domain') . '/portfolio')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($portfolioSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $portfolioSettings->hero_badge }}</span>
        @endif
        @if($portfolioSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $portfolioSettings->hero_title !!}</h1>
        @endif
        @if($portfolioSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $portfolioSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.portfolio', ['portfolios' => $portfolios, 'preview' => false])

@include('frontend.components.cta-banner')
@endsection
