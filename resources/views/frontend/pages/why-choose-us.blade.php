@php $pageKey = 'why-choose-us'; @endphp
@extends('frontend.layout.app')

@section('title', $whyChooseUsSettings->meta_title ?: config('xcodrix.pages.why-choose-us.title'))
@section('meta_description', $whyChooseUsSettings->meta_description ?: config('xcodrix.pages.why-choose-us.description'))
@section('canonical', config('xcodrix.domain') . '/why-choose-us')

@push('head')
@include('frontend.partials.schema-organization')
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($whyChooseUsSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $whyChooseUsSettings->hero_badge }}</span>
        @endif
        @if($whyChooseUsSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $whyChooseUsSettings->hero_title !!}</h1>
        @endif
        @if($whyChooseUsSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $whyChooseUsSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.why-choose-us', ['items' => $whyChooseUsItems, 'preview' => false])

<section class="py-20 bg-xc-dark/40">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid lg:grid-cols-2 gap-12 items-center scroll-reveal">
        <img src="{{ $whyChooseUsSettings->partnerImageUrl() }}" alt="Why choose XCodrix for software development" class="rounded-2xl w-full" width="560" height="400" loading="lazy">
        <div class="space-y-6">
            @if($whyChooseUsSettings->partner_title)
                <h2 class="text-3xl font-bold text-white">{{ $whyChooseUsSettings->partner_title }}</h2>
            @endif
            @if($whyChooseUsSettings->partner_content)
                <p class="text-slate-400 leading-relaxed">{{ $whyChooseUsSettings->partner_content }}</p>
            @endif
            @if($whyChooseUsSettings->partnerPointsList())
                <ul class="space-y-3">
                    @foreach($whyChooseUsSettings->partnerPointsList() as $point)
                        <li class="flex items-center gap-3 text-slate-300">
                            <svg class="w-5 h-5 text-xc-cyan flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ $point }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
