@props(['preview' => true])

<section class="py-20 bg-xc-dark/40" aria-labelledby="process-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Our Process',
                'title' => "How We <span class='xc-gradient-text'>Build</span> Software",
                'subtitle' => 'A proven 6-step process from idea to launch and beyond.',
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(config('xcodrix.process') as $step)
                <div class="xc-card scroll-reveal relative">
                    <span class="text-3xl font-extrabold xc-gradient-text mb-3 block">{{ $step['step'] }}</span>
                    <h3 class="text-lg font-bold text-white mb-2">{{ $step['title'] }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">{{ $step['description'] }}</p>
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
