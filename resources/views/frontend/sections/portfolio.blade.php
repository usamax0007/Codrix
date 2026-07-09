@props(['preview' => true, 'limit' => 3])

<section class="py-20" @if($preview) id="portfolio" @endif aria-labelledby="portfolio-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Portfolio',
                'title' => "Case Studies & <span class='xc-gradient-text'>Projects</span>",
                'subtitle' => 'Real solutions built for real businesses.',
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(array_slice(config('xcodrix.portfolio'), 0, $preview ? $limit : 999) as $project)
                <article class="xc-card group overflow-hidden p-0 scroll-reveal">
                    <div class="overflow-hidden">
                        <img src="{{ asset('images/' . $project['image']) }}"
                             alt="{{ $project['title'] }} — {{ $project['category'] }}"
                             class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500"
                             width="400" height="240" loading="lazy">
                    </div>
                    <div class="p-6">
                        <span class="text-xs font-semibold text-xc-cyan uppercase tracking-wider">{{ $project['category'] }}</span>
                        <h3 class="text-lg font-bold text-white mt-2 mb-2">{{ $project['title'] }}</h3>
                        <p class="text-slate-400 text-sm">{{ $project['description'] }}</p>
                    </div>
                </article>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/portfolio') }}" class="xc-btn-primary">View All Case Studies</a>
            </div>
        @endif
    </div>
</section>
