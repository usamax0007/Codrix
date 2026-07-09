@props(['preview' => true])

<section class="py-20 {{ $preview ? '' : 'xc-page-hero' }}" @if($preview) id="about" @endif aria-labelledby="about-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'About XCodrix',
                'title' => "Who We <span class='xc-gradient-text'>Are</span>",
                'subtitle' => 'A premium software development agency helping businesses build scalable digital products.',
            ])
        @endif

        <div class="grid lg:grid-cols-2 gap-12 items-center scroll-reveal">
            <div class="space-y-6">
                @unless($preview)
                    <span class="xc-badge">About XCodrix</span>
                    <h1 id="about-heading" class="text-4xl md:text-5xl font-bold">We Build Digital Products That Drive Growth</h1>
                @endunless
                <p class="text-slate-400 text-lg leading-relaxed">
                    XCodrix is a software development agency that partners with startups and enterprises to design, build, and scale custom software. We specialize in AI-powered applications, SaaS platforms, Laravel backends, Vue.js frontends, mobile apps, and Twilio communication systems.
                </p>
                <p class="text-slate-400 leading-relaxed">
                    With 12+ years of experience and 150+ projects delivered, our team combines deep technical expertise with a transparent, client-first approach. We don't just write code — we solve business problems.
                </p>
                @if($preview)
                    <a href="{{ url('/about') }}" class="xc-btn-link">Learn more about us →</a>
                @endif
            </div>
            <div class="grid grid-cols-2 gap-4">
                <img src="{{ asset('images/about-1.webp') }}" alt="XCodrix development team collaborating" class="rounded-xl w-full" width="300" height="200" loading="lazy">
                <img src="{{ asset('images/about-2.webp') }}" alt="Modern software development workspace" class="rounded-xl w-full mt-8" width="300" height="200" loading="lazy">
            </div>
        </div>
    </div>
</section>
