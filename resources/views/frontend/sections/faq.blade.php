@props(['preview' => true, 'limit' => 4])

<section class="py-20" @if($preview) id="faq" @endif aria-labelledby="faq-heading">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'FAQ',
                'title' => "Frequently Asked <span class='xc-gradient-text'>Questions</span>",
                'subtitle' => 'Common questions about XCodrix services, process, and pricing.',
            ])
        @endif

        <div class="space-y-3">
            @foreach(array_slice(config('xcodrix.faq'), 0, $preview ? $limit : 999) as $faq)
                <div class="faq-item xc-card !p-0 overflow-hidden scroll-reveal">
                    <button type="button" class="faq-question w-full flex justify-between items-center p-5 text-left font-semibold text-white hover:text-xc-cyan transition-colors">
                        {{ $faq['q'] }}
                        <span class="faq-icon text-xc-cyan text-xl transition-transform flex-shrink-0 ml-4">+</span>
                    </button>
                    <div class="faq-answer px-5">
                        <p class="text-slate-400 text-sm leading-relaxed pb-5">{{ $faq['a'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/faq') }}" class="xc-btn-link">View all FAQs →</a>
            </div>
        @endif
    </div>
</section>
