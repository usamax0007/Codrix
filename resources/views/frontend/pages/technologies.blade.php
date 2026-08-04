@php $pageKey = 'technologies'; @endphp
@extends('frontend.layout.app')

@section('title', $technologySettings->meta_title ?: config('xcodrix.pages.technologies.title'))
@section('meta_description', $technologySettings->meta_description ?: config('xcodrix.pages.technologies.description'))
@section('canonical', config('xcodrix.domain') . '/technologies')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($technologySettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $technologySettings->hero_badge }}</span>
        @endif
        @if($technologySettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $technologySettings->hero_title !!}</h1>
        @endif
        @if($technologySettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $technologySettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.technologies', ['technologyCategories' => $technologyCategories, 'preview' => false])

@if($technologySettings->bottom_title || $technologySettings->bottom_content || $technologySettings->bottom_content_2)
<section class="py-20">
    <div class="max-w-3xl mx-auto px-4 space-y-6 text-slate-400 leading-relaxed scroll-reveal">
        @if($technologySettings->bottom_title)
            <h2 class="text-2xl font-bold text-white">{{ $technologySettings->bottom_title }}</h2>
        @endif
        @if($technologySettings->bottom_content)
            <p>{{ $technologySettings->bottom_content }}</p>
        @endif
        @if($technologySettings->bottom_content_2)
            <p>{{ $technologySettings->bottom_content_2 }}</p>
        @endif
    </div>
</section>
@endif

@include('frontend.components.cta-banner')
@endsection
