@props(['preview' => true])

<section class="py-20" aria-labelledby="why-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Why Choose Us',
                'title' => "Why Companies Trust <span class='xc-gradient-text'>XCodrix</span>",
                'subtitle' => 'We combine technical excellence with a partnership mindset.',
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(config('xcodrix.why_choose_us') as $item)
                <div class="xc-card scroll-reveal">
                    <div class="w-10 h-10 rounded-lg bg-xc-cyan/10 flex items-center justify-center text-xc-cyan mb-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">{{ $item['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $item['description'] }}</p>
                </div>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/why-choose-us') }}" class="xc-btn-link">Learn why clients choose us →</a>
            </div>
        @endif
    </div>
</section>
