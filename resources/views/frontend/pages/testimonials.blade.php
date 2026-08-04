@php $pageKey = 'testimonials'; @endphp
@extends('frontend.layout.app')

@section('title', $testimonialSettings->meta_title ?: config('xcodrix.pages.testimonials.title'))
@section('meta_description', $testimonialSettings->meta_description ?: config('xcodrix.pages.testimonials.description'))
@section('canonical', config('xcodrix.domain') . '/testimonials')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'itemListElement' => collect($testimonials)->map(fn($t, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'item' => [
            '@type' => 'Review',
            'author' => ['@type' => 'Person', 'name' => $t->name],
            'reviewBody' => $t->text,
        ],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($testimonialSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $testimonialSettings->hero_badge }}</span>
        @endif
        @if($testimonialSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $testimonialSettings->hero_title !!}</h1>
        @endif
        @if($testimonialSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $testimonialSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.testimonials', ['testimonials' => $testimonials, 'preview' => false])

@include('frontend.components.cta-banner')
@endsection
