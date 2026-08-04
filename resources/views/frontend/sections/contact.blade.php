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
                    Whether you need an MVP, a SaaS platform, AI integration, or a Twilio communication system — {{ $siteSettings->site_name }} is ready to help. Book a free consultation and receive a tailored proposal within 48 hours.
                </p>
                <div class="space-y-4">
                    @if($siteSettings->email)
                        <div class="flex items-center gap-3 text-slate-300">
                            <svg class="w-5 h-5 text-xc-cyan shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <a href="mailto:{{ $siteSettings->email }}" class="hover:text-xc-cyan transition-colors">{{ $siteSettings->email }}</a>
                        </div>
                    @endif
                    @if($siteSettings->phone)
                        <div class="flex items-center gap-3 text-slate-300">
                            <svg class="w-5 h-5 text-xc-cyan shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                            <a href="tel:{{ preg_replace('/\s+/', '', $siteSettings->phone) }}" class="hover:text-xc-cyan transition-colors">{{ $siteSettings->phone }}</a>
                        </div>
                    @endif
                    @if($siteSettings->address)
                        <div class="flex items-start gap-3 text-slate-300">
                            <svg class="w-5 h-5 text-xc-cyan shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $siteSettings->address }}</span>
                        </div>
                    @endif
                    <div class="flex items-center gap-3 text-slate-300">
                        <svg class="w-5 h-5 text-xc-cyan shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/></svg>
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
