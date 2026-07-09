@php $pageKey = 'services'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.services.title'))
@section('meta_description', config('xcodrix.pages.services.description'))
@section('canonical', config('xcodrix.domain') . '/services')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ItemList',
    'name' => 'XCodrix Services',
    'itemListElement' => collect(config('xcodrix.services'))->map(fn($s, $i) => [
        '@type' => 'ListItem',
        'position' => $i + 1,
        'item' => ['@type' => 'Service', 'name' => $s['title'], 'description' => $s['summary']],
    ])->values(),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Services</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Our <span class="xc-gradient-text">Services</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Comprehensive software development services — from AI and SaaS to Laravel, Vue.js, Twilio, and mobile apps.</p>
    </div>
</section>

@include('frontend.sections.services', ['preview' => false])

<section class="py-20">
    <div class="max-w-4xl mx-auto px-4 text-center scroll-reveal">
        <h2 class="text-2xl font-bold text-white mb-4">Why Choose XCodrix for Your Project?</h2>
        <p class="text-slate-400 leading-relaxed">Every XCodrix service is delivered by senior engineers with deep domain expertise. We don't outsource, we don't cut corners, and we don't disappear after launch. From the first consultation to post-launch support, you work directly with the team building your product.</p>
    </div>
</section>

@include('frontend.components.cta-banner')
@endsection
