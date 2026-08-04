@php $pageKey = 'faq'; @endphp
@extends('frontend.layout.app')

@section('title', $faqSettings->meta_title ?: config('xcodrix.pages.faq.title'))
@section('meta_description', $faqSettings->meta_description ?: config('xcodrix.pages.faq.description'))
@section('canonical', config('xcodrix.domain') . '/faq')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect($faqs)->map(fn($f) => [
        '@type' => 'Question',
        'name' => $f->question,
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f->answer],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        @if($faqSettings->hero_badge)
            <span class="xc-badge mb-4 inline-block">{{ $faqSettings->hero_badge }}</span>
        @endif
        @if($faqSettings->hero_title)
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{!! $faqSettings->hero_title !!}</h1>
        @endif
        @if($faqSettings->hero_subtitle)
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">{{ $faqSettings->hero_subtitle }}</p>
        @endif
    </div>
</section>

@include('frontend.sections.faq', ['faqs' => $faqs, 'preview' => false])

@include('frontend.components.cta-banner')
@endsection
