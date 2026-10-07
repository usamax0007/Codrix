<footer class="bg-xc-dark border-t border-white/5 pt-16 pb-8" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            <div class="lg:col-span-1">
                <img src="{{ asset('images/xcodrix-logo-dark.svg') }}" alt="{{ $siteSettings->site_name }}" class="h-10 w-auto mb-4" width="140" height="40">
                <p class="text-slate-400 text-sm leading-relaxed mb-6">
                    Software studio in Multan, Pakistan building AI voice agents, VoIP systems, and WhatsApp automation for startups and SMBs.
                </p>
                <div class="flex gap-3" aria-label="Social media links">
                    <a href="{{ config('xcodrix.social.linkedin') }}" rel="noopener" target="_blank" class="p-2.5 rounded-full border border-white/10 hover:border-xc-cyan hover:text-xc-cyan transition-colors" aria-label="LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.359V9h3.414v1.565h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.282zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </a>
                    <a href="{{ config('xcodrix.social.linkedin_founder') }}" rel="noopener" target="_blank" class="p-2.5 rounded-full border border-white/10 hover:border-xc-cyan hover:text-xc-cyan transition-colors" aria-label="Founder LinkedIn">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/></svg>
                    </a>
                </div>
            </div>

            <nav aria-label="Company links">
                <h2 class="font-semibold text-white mb-4">Company</h2>
                <ul class="space-y-2.5 text-sm text-slate-400">
                    <li><a href="{{ url('/about') }}" class="hover:text-xc-cyan transition-colors">About {{ $siteSettings->site_name }}</a></li>
                    <li><a href="{{ url('/why-choose-us') }}" class="hover:text-xc-cyan transition-colors">Why Choose Us</a></li>
                    <li><a href="{{ url('/process') }}" class="hover:text-xc-cyan transition-colors">Development Process</a></li>
                </ul>
            </nav>

            <nav aria-label="Services links">
                <h2 class="font-semibold text-white mb-4">Services</h2>
                <ul class="space-y-2.5 text-sm text-slate-400">
                    @foreach(($footerServices ?? collect()) as $service)
                        <li><a href="{{ url('/services') }}#{{ $service->slug }}" class="hover:text-xc-cyan transition-colors">{{ $service->title }}</a></li>
                    @endforeach
                    <li><a href="{{ url('/services') }}" class="text-xc-cyan hover:underline">View all services →</a></li>
                </ul>
            </nav>

            <div>
                <h2 class="font-semibold text-white mb-4">Contact</h2>
                <address class="not-italic text-sm text-slate-400 space-y-2">
                    <p><a href="mailto:{{ config('xcodrix.email') }}" class="hover:text-xc-cyan transition-colors">{{ config('xcodrix.email') }}</a></p>
                    <p>Multan, Punjab, Pakistan</p>
                </address>
                @include('frontend.components.book-a-call-button', ['class' => 'inline-flex xc-btn-primary text-sm mt-4 !py-2 !px-4', 'label' => 'Book a call'])
            </div>
        </div>

        <div class="border-t border-white/5 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4 text-sm text-slate-400">
            <p>&copy; {{ date('Y') }} <span class="text-white font-medium">{{ $siteSettings->site_name }}</span>. All rights reserved.</p>
            <div class="flex gap-6">
                <a href="{{ url('/privacy') }}" class="text-slate-400 hover:text-xc-cyan transition-colors">Privacy</a>
                <a href="{{ url('/terms') }}" class="text-slate-400 hover:text-xc-cyan transition-colors">Terms</a>
                <a href="{{ url('/faq') }}" class="text-slate-400 hover:text-xc-cyan transition-colors">FAQ</a>
                <a href="{{ url('/sitemap.xml') }}" class="text-slate-400 hover:text-xc-cyan transition-colors">Sitemap</a>
            </div>
        </div>
    </div>
</footer>
