@props(['preview' => true])

<section class="py-20 bg-xc-dark/40" aria-labelledby="tech-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Technologies',
                'title' => "Tech Stack We <span class='xc-gradient-text'>Master</span>",
                'subtitle' => 'Modern, battle-tested technologies chosen for performance and scalability.',
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(config('xcodrix.technologies') as $category => $techs)
                <div class="xc-card scroll-reveal">
                    <h3 class="text-lg font-bold text-white mb-4">{{ $category }}</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($techs as $tech)
                            <span class="text-sm px-3 py-1.5 rounded-lg bg-white/5 text-slate-300 border border-white/5">{{ $tech }}</span>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/technologies') }}" class="xc-btn-link">Explore our full tech stack →</a>
            </div>
        @endif
    </div>
</section>
