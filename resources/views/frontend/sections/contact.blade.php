@props(['preview' => true])

<section class="py-20" @if($preview) id="contact" @endif aria-labelledby="contact-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Contact',
                'title' => "Let's Build Something <span class='xc-gradient-text'>Great</span>",
                'subtitle' => 'Tell us about your project. We respond within 24 hours.',
            ])
        @endif

        <div class="grid lg:grid-cols-2 gap-12 scroll-reveal">
            <div class="space-y-6">
                <p class="text-slate-400 leading-relaxed">
                    Whether you need an MVP, a SaaS platform, AI integration, or a Twilio communication system — XCodrix is ready to help. Book a free consultation and receive a tailored proposal within 48 hours.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-3 text-slate-300">
                        <svg class="w-5 h-5 text-xc-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <a href="mailto:{{ config('xcodrix.email') }}" class="hover:text-xc-cyan transition-colors">{{ config('xcodrix.email') }}</a>
                    </div>
                    <div class="flex items-center gap-3 text-slate-300">
                        <svg class="w-5 h-5 text-xc-cyan" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
                        <a href="{{ config('xcodrix.domain') }}" class="hover:text-xc-cyan transition-colors">xcodrix.com</a>
                    </div>
                </div>
                @if($preview)
                    <a href="{{ url('/contact') }}" class="xc-btn-primary inline-flex">Go to Contact Page</a>
                @endif
            </div>

            @unless($preview)
                @include('frontend.partials.contact-form')
            @else
                <div class="xc-card">
                    <h3 class="font-semibold text-white mb-4">Quick Contact</h3>
                    <p class="text-slate-400 text-sm mb-6">Fill out our full contact form for a detailed project discussion.</p>
                    <a href="{{ url('/contact') }}" class="xc-btn-primary w-full text-center">Contact Us</a>
                </div>
            @endunless
        </div>
    </div>
</section>
