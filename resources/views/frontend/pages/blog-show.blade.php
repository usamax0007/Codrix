@php $pageKey = 'blog'; @endphp
@extends('frontend.layout.app')

@section('title', ($post->meta_title ?: $post->title) . ' | XCodrix')
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('canonical', config('xcodrix.domain') . '/blog/' . $post->slug)

@push('head')
    @include('frontend.partials.schema-organization')
    <script type="application/ld+json">
        {!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->excerpt,
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at?->toIso8601String(),
            'url' => config('xcodrix.domain') . '/blog/' . $post->slug,
            'author' => ['@type' => 'Organization', 'name' => 'XCodrix'],
            'publisher' => ['@type' => 'Organization', 'name' => 'XCodrix'],
        ], JSON_UNESCAPED_SLASHES) !!}
    </script>
@endpush

@section('content')
    <article>
        <section class="xc-page-hero">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 scroll-reveal">
                <div class="text-left mb-4">
                    <a href="{{ url('/blog') }}" class="text-sm text-xc-cyan hover:underline inline-block">&larr; Back to Blog</a>
                </div>

                <div class="text-center">
                    @if($post->category)
                        <span class="xc-badge mb-4 inline-block">{{ $post->category }}</span>
                    @endif
                    <h1 class="text-3xl md:text-5xl font-bold mb-4">{{ $post->title }}</h1>
                    <div class="flex flex-wrap justify-center gap-4 text-sm text-slate-400">
                        <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('F d, Y') }}</time>
                        @if($post->read_time)
                            <span>{{ $post->read_time }} read</span>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <section class="pb-20">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                @if($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}"
                         class="w-full rounded-xl mb-10 scroll-reveal">
                @endif

                <div class="prose prose-invert prose-lg max-w-none text-slate-300 leading-relaxed scroll-reveal blog-content">
                    {!! $post->content !!}
                </div>
            </div>
        </section>
    </article>

    @include('frontend.components.cta-banner')
@endsection
