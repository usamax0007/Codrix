@props(['preview' => true, 'limit' => 3])

<section class="py-20 bg-xc-dark/40" @if($preview) id="insights" @endif aria-labelledby="blog-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Insights',
                'title' => "Blog & <span class='xc-gradient-text'>Articles</span>",
                'subtitle' => 'Expert insights on software development, AI, and technology.',
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(array_slice(config('xcodrix.blog'), 0, $preview ? $limit : 999) as $post)
                <article class="xc-card scroll-reveal">
                    <span class="text-xs font-semibold text-xc-cyan">{{ $post['category'] }}</span>
                    <h3 class="text-lg font-bold text-white mt-2 mb-2">{{ $post['title'] }}</h3>
                    <p class="text-slate-400 text-sm mb-4">{{ $post['excerpt'] }}</p>
                    <div class="flex justify-between items-center text-xs text-slate-500">
                        <time datetime="{{ $post['date'] }}">{{ date('M d, Y', strtotime($post['date'])) }}</time>
                        <span>{{ $post['read_time'] }} read</span>
                    </div>
                </article>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/blog') }}" class="xc-btn-primary">Read All Articles</a>
            </div>
        @endif
    </div>
</section>
