<section class="py-20 bg-xc-dark/40" aria-labelledby="industries-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @include('frontend.components.section-heading', [
            'badge' => 'Industries',
            'title' => "Industries We <span class='xc-gradient-text'>Serve</span>",
            'subtitle' => 'Deep domain expertise across multiple sectors.',
        ])

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach(array_slice(config('xcodrix.industries'), 0, 4) as $industry)
                <div class="xc-card text-center scroll-reveal">
                    <div class="w-12 h-12 rounded-xl bg-xc-cyan/10 flex items-center justify-center text-xc-cyan mx-auto mb-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                    <h3 class="font-bold text-white mb-2">{{ $industry['name'] }}</h3>
                    <p class="text-slate-400 text-sm">{{ $industry['description'] }}</p>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-10 scroll-reveal">
            <a href="{{ url('/industries') }}" class="xc-btn-link">See all industries →</a>
        </div>
    </div>
</section>
