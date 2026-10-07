@php $pageKey = 'home'; @endphp
@extends('frontend.layout.app')

@section('title', config('xcodrix.pages.home.title'))
@section('meta_description', config('xcodrix.pages.home.description'))
@section('canonical', config('app.url'))

@push('head')
@include('frontend.partials.schema-organization')
<script type="application/ld+json">
{!! json_encode([
    '@' . 'context' => 'https://schema.org',
    '@type' => 'WebSite',
    'name' => 'Xcodrix',
    'url' => config('app.url'),
    'description' => config('xcodrix.pages.home.description'),
    'publisher' => ['@type' => 'Organization', 'name' => 'Xcodrix'],
], JSON_UNESCAPED_SLASHES) !!}
</script>
@endpush

@section('content')
    @include('frontend.sections.hero')
    @include('frontend.sections.built-with')
    @include('frontend.sections.about', ['preview' => true])
    @include('frontend.sections.services', ['services' => $services, 'preview' => true])
    @include('frontend.sections.why-choose-us', ['items' => $whyChooseUsItems, 'preview' => true])
    @include('frontend.sections.process', ['preview' => true])
    @if(count($portfolios) > 0)
        @include('frontend.sections.portfolio', ['portfolios' => $portfolios, 'preview' => true])
    @endif
    @include('frontend.sections.faq', ['faqs' => $faqs, 'preview' => true])
    @if(count($blogPosts) > 0)
        @include('frontend.sections.blog', ['posts' => $blogPosts, 'preview' => true])
    @endif
    @include('frontend.components.cta-banner')
    @include('frontend.sections.contact', ['preview' => true])
@endsection
