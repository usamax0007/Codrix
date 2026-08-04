@php $pageKey = 'process'; @endphp
@extends('frontend.layout.app')

@section('title', $processSettings->meta_title ?: config('xcodrix.pages.process.title'))
@section('meta_description', $processSettings->meta_description ?: config('xcodrix.pages.process.description'))
@section('canonical', config('xcodrix.domain') . '/process')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($processSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $processSettings->hero_badge }}</span>
        @endif
        @if($processSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $processSettings->hero_title !!}</h1>
        @endif
        @if($processSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $processSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.process', ['preview' => false])

@if($processSettings->footer_title || $processSettings->footer_content_1 || $processSettings->footer_content_2)
    <section class="py-20">
        <div class="max-w-3xl mx-auto px-4 space-y-6 text-slate-400 leading-relaxed scroll-reveal">
            @if($processSettings->footer_title)
                <h2 class="text-2xl font-bold text-white">{{ $processSettings->footer_title }}</h2>
            @endif
            @if($processSettings->footer_content_1)
                <p>{{ $processSettings->footer_content_1 }}</p>
            @endif
            @if($processSettings->footer_content_2)
                <p>{{ $processSettings->footer_content_2 }}</p>
            @endif
        </div>
    </section>
@endif

@include('frontend.components.cta-banner')
@endsection
