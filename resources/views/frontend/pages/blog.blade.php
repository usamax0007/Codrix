@php $pageKey = 'blog'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.blog.title'))
@section('meta_description', config('xcodrix.pages.blog.description'))
@section('canonical', config('xcodrix.domain') . '/blog')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Blog',
    'name' => 'XCodrix Blog & Insights',
    'url' => config('xcodrix.domain') . '/blog',
    'publisher' => ['@type' => 'Organization', 'name' => 'XCodrix'],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Blog & Insights</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Software Development <span class="xc-gradient-text">Insights</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Expert articles on Laravel, AI, Twilio, SaaS development, and startup strategy.</p>
    </div>
</section>

@include('frontend.sections.blog', ['preview' => false])

@include('frontend.components.cta-banner')
@endsection
