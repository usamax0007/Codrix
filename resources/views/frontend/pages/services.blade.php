@php $pageKey = 'services'; @endphp
@extends('frontend.layout.app')

@section('title', $serviceSettings->meta_title ?: config('xcodrix.pages.services.title'))
@section('meta_description', $serviceSettings->meta_description ?: config('xcodrix.pages.services.description'))
@section('canonical', config('xcodrix.domain') . '/services')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => ($siteSettings->site_name ?? 'XCodrix') . ' Services',
    'itemListElement' => collect($services)->map(fn($s, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'item' => ['@type' => 'Service', 'name' => $s->title, 'description' => $s->summary],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($serviceSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $serviceSettings->hero_badge }}</span>
        @endif
        @if($serviceSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $serviceSettings->hero_title !!}</h1>
        @endif
        @if($serviceSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $serviceSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.services', ['services' => $services, 'preview' => false])

@if($serviceSettings->footer_title || $serviceSettings->footer_content)
    <section class="py-20">
        <div class="max-w-4xl mx-auto px-4 text-center scroll-reveal">
            @if($serviceSettings->footer_title)
                <h2 class="text-2xl font-bold text-white mb-4">{{ $serviceSettings->footer_title }}</h2>
            @endif
            @if($serviceSettings->footer_content)
                <p class="text-slate-400 leading-relaxed">{{ $serviceSettings->footer_content }}</p>
            @endif
        </div>
    </section>
@endif

@include('frontend.components.cta-banner')
@endsection
