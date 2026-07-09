@php $pageKey = 'contact'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.contact.title'))
@section('meta_description', config('xcodrix.pages.contact.description'))
@section('canonical', config('xcodrix.domain') . '/contact')

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'ContactPage',
    'name' => 'Contact XCodrix',
    'url' => config('xcodrix.domain') . '/contact',
    'description' => config('xcodrix.pages.contact.description'),
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
<section class="xc-page-hero">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center scroll-reveal">
        <span class="xc-badge mb-4 inline-block">Contact</span>
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Get in <span class="xc-gradient-text">Touch</span></h1>
        <p class="text-slate-400 text-lg max-w-2xl mx-auto">Book a free consultation. We respond within 24 hours with a tailored proposal.</p>
    </div>
</section>

@include('frontend.sections.contact', ['preview' => false])
@endsection
