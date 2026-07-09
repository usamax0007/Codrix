@php $pageKey = 'home'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.home.title'))
@section('meta_description', config('xcodrix.pages.home.description'))
@section('canonical', config('xcodrix.domain'))

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'XCodrix',
    'url' => config('xcodrix.domain'),
    'description' => config('xcodrix.pages.home.description'),
    'publisher' => ['@type' => 'Organization', 'name' => 'XCodrix'],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
    @include('frontend.sections.hero')
    @include('frontend.sections.clients')
    @include('frontend.sections.stats')
    @include('frontend.sections.about', ['preview' => true])
    @include('frontend.sections.services', ['preview' => true])
    @include('frontend.sections.why-choose-us', ['preview' => true])
    @include('frontend.sections.process', ['preview' => true])
    @include('frontend.sections.industries-preview')
    @include('frontend.sections.technologies', ['preview' => true])
    @include('frontend.sections.portfolio', ['preview' => true])
    @include('frontend.sections.testimonials', ['preview' => true])
    @include('frontend.sections.faq', ['preview' => true])
    @include('frontend.sections.blog', ['preview' => true])
    @include('frontend.components.cta-banner')
    @include('frontend.sections.contact', ['preview' => true])
@endsection
