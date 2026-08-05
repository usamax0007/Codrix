@props(['preview' => true])

<section class="py-20 bg-xc-dark/40" aria-labelledby="process-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => $processSettings->section_badge,
                'title' => $processSettings->section_title,
                'subtitle' => $processSettings->section_subtitle,
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($processSettings->steps() as $step)
                <div class="xc-card scroll-reveal relative">
                    @if($step['step'])
                        <span class="text-3xl font-extrabold xc-gradient-text mb-3 block">{{ $step['step'] }}</span>
                    @endif
                    @if($step['title'])
                        <h3 class="text-lg font-bold text-white mb-2">{{ $step['title'] }}</h3>
                    @endif
                    @if($step['description'])
                        <p class="text-slate-400 text-sm leading-relaxed">{{ $step['description'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/process') }}" class="xc-btn-link">See our full process →</a>
            </div>
        @endif
    </div>
</section>
