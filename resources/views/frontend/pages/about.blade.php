@php $pageKey = 'about'; @endphp
@extends('frontend.layout.app')

@section('title', $aboutSettings->meta_title ?: config('xcodrix.pages.about.title'))
@section('meta_description', $aboutSettings->meta_description ?: config('xcodrix.pages.about.description'))
@section('canonical', config('xcodrix.domain') . '/about')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($aboutSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $aboutSettings->hero_badge }}</span>
        @endif
        @if($aboutSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ $aboutSettings->hero_title }}</h1>
        @endif
        @if($aboutSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $aboutSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.about', ['preview' => false])

<section class="py-20 bg-xc-dark/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($aboutSettings->stats())
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 mb-16">
                @foreach($aboutSettings->stats() as $stat)
                    <div class="text-center scroll-reveal">
                        <div class="stat-value mb-2">{{ $stat['value'] }}</div>
                        <p class="text-slate-400 text-sm">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="max-w-3xl mx-auto space-y-6 text-slate-400 leading-relaxed scroll-reveal">
            @if($aboutSettings->who_we_help_title || $aboutSettings->who_we_help_content)
                @if($aboutSettings->who_we_help_title)
                    <h2 class="text-2xl font-bold text-white">{{ $aboutSettings->who_we_help_title }}</h2>
                @endif
                @if($aboutSettings->who_we_help_content)
                    <p>{{ $aboutSettings->who_we_help_content }}</p>
                @endif
            @endif

            @if($aboutSettings->what_we_do_title || $aboutSettings->what_we_do_content)
                @if($aboutSettings->what_we_do_title)
                    <h2 class="text-2xl font-bold text-white pt-4">{{ $aboutSettings->what_we_do_title }}</h2>
                @endif
                @if($aboutSettings->what_we_do_content)
                    <p>{{ $aboutSettings->what_we_do_content }}</p>
                @endif
            @endif

            @if($aboutSettings->mission_title || $aboutSettings->mission_content)
                @if($aboutSettings->mission_title)
                    <h2 class="text-2xl font-bold text-white pt-4">{{ $aboutSettings->mission_title }}</h2>
                @endif
                @if($aboutSettings->mission_content)
                    <p>{{ $aboutSettings->mission_content }}</p>
                @endif
            @endif
        </div>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
