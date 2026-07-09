@php $pageKey = 'faq'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.faq.title'))
@section('meta_description', config('xcodrix.pages.faq.description'))
@section('canonical', config('xcodrix.domain') . '/faq')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => collect(config('xcodrix.faq'))->map(fn($f) => [
        '@type' => 'Question',
        'name' => $f['q'],
        'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">FAQ</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Frequently Asked <span class="xc-gradient-text">Questions</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Everything you need to know about working with XCodrix.</p>
    </div>
</section>

@include('frontend.sections.faq', ['preview' => false])

@include('frontend.components.cta-banner')
@endsection
