@props(['preview' => true])

<section class="py-20 {{ $preview ? '' : 'xc-page-hero' }}" @if($preview) id="about" @endif aria-labelledby="about-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => $aboutSettings->section_badge ?: 'About XCodrix',
                'title' => $aboutSettings->section_title ?: "Who We <span class='xc-gradient-text'>Are</span>",
                'subtitle' => $aboutSettings->section_subtitle ?: 'A premium software development agency helping businesses build scalable digital products.',
            ])
        @endif

        <div class="grid lg:grid-cols-2 gap-12 items-center scroll-reveal">
            <div class="space-y-6">
                @unless($preview)
                    @if($aboutSettings->section_badge)
                        <span class="xc-badge">{{ $aboutSettings->section_badge }}</span>
                    @endif
                    @if($aboutSettings->intro_heading)
                        <h1 id="about-heading" class="text-4xl md:text-5xl font-bold">{{ $aboutSettings->intro_heading }}</h1>
                    @endif
                @endunless
                @if($aboutSettings->intro_paragraph_1)
                    <p class="text-slate-400 text-lg leading-relaxed">
                        {{ $aboutSettings->intro_paragraph_1 }}
                    </p>
                @endif
                @if($aboutSettings->intro_paragraph_2)
                    <p class="text-slate-400 leading-relaxed">
                        {{ $aboutSettings->intro_paragraph_2 }}
                    </p>
                @endif
                @if($preview)
                    <a href="{{ url('/about') }}" class="xc-btn-link">Learn more about us →</a>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="{{ $aboutSettings->image1Url() }}" alt="{{ $siteSettings->site_name }} development team collaborating" class="rounded-xl w-full" width="300" height="200" loading="lazy">
                <img src="{{ $aboutSettings->image2Url() }}" alt="Modern software development workspace" class="rounded-xl w-full mt-8" width="300" height="200" loading="lazy">
            </div>
        </div>
    </div>
</section>
