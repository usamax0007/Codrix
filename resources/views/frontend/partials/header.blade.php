<header role="banner">
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-xc-dark/80 backdrop-blur-md border-b border-white/5"
         aria-label="Main navigation">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="flex items-center gap-3" aria-label="XCodrix Home">
                <img src="{{ asset('images/xcodrix-logo.png') }}" alt="XCodrix — Software Development Agency" class="h-10 w-auto" width="140" height="40">
            </a>

            <ul class="hidden lg:flex items-center gap-7 text-sm font-medium text-slate-300">
                <li><a href="{{ url('/about') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('about') ? 'text-xc-cyan' : '' }}">About</a></li>
                <li><a href="{{ url('/services') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('services') ? 'text-xc-cyan' : '' }}">Services</a></li>
                <li><a href="{{ url('/process') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('process') ? 'text-xc-cyan' : '' }}">Process</a></li>
                <li><a href="{{ url('/portfolio') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('portfolio') ? 'text-xc-cyan' : '' }}">Portfolio</a></li>
                <li><a href="{{ url('/blog') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('blog') ? 'text-xc-cyan' : '' }}">Insights</a></li>
                <li><a href="{{ url('/faq') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('faq') ? 'text-xc-cyan' : '' }}">FAQ</a></li>
                <li><a href="{{ url('/contact') }}" class="hover:text-xc-cyan transition-colors {{ request()->is('contact') ? 'text-xc-cyan' : '' }}">Contact</a></li>
            </ul>

            <div class="flex items-center gap-3">
                <a href="{{ url('/contact') }}" class="hidden sm:inline-flex xc-btn-primary text-sm !py-2.5 !px-5">
                    Book a Call
                </a>
                <button id="mobile-menu-btn" type="button" class="lg:hidden text-white p-2" aria-label="Open menu" aria-expanded="false">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="fixed inset-0 bg-xc-darker z-[60] hidden lg:hidden" aria-hidden="true">
            <div class="p-6 h-full overflow-y-auto">
                <div class="flex justify-between items-center mb-10">
                    <img src="{{ asset('images/xcodrix-logo.png') }}" alt="XCodrix" class="h-9 w-auto">
                    <button id="close-menu-btn" type="button" class="text-white p-2" aria-label="Close menu">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <ul class="space-y-5 text-lg">
                    @foreach(['About' => '/about', 'Services' => '/services', 'Process' => '/process', 'Portfolio' => '/portfolio', 'Insights' => '/blog', 'FAQ' => '/faq', 'Contact' => '/contact'] as $label => $path)
                        <li><a href="{{ url($path) }}" class="block text-slate-300 hover:text-white">{{ $label }}</a></li>
                    @endforeach
                    <li class="pt-4"><a href="{{ url('/contact') }}" class="xc-btn-primary w-full text-center">Book a Call</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>
