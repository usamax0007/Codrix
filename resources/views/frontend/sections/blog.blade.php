@props(['posts', 'preview' => true])

<section class="py-20 bg-xc-dark/40" @if($preview) id="insights" @endif aria-labelledby="blog-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => 'Insights',
                'title' => "Blog & <span class='xc-gradient-text'>Articles</span>",
                'subtitle' => 'Expert insights on software development, AI, and technology.',
            ])
        @endif

        @if($posts->isEmpty())
            <p class="text-center text-slate-400">No articles published yet. Check back soon.</p>
        @else
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 items-end">
                @foreach($posts as $post)
                    <article class="xc-card scroll-reveal flex flex-col justify-end h-full">
                        <a href="{{ url('/blog/' . $post->slug) }}" class="block group">
                            @if($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" class="w-full h-40 object-cover rounded-lg mb-4">
                            @endif
                            @if($post->category)
                                <span class="text-xs font-semibold text-xc-cyan">{{ $post->category }}</span>
                            @endif
                            <h3 class="text-lg font-bold text-white mt-2 mb-2 group-hover:text-xc-cyan transition-colors">{{ $post->title }}</h3>
                            <p class="text-slate-400 text-sm mb-4">{{ $post->excerpt }}</p>
                            <div class="flex justify-between items-center text-xs text-slate-500">
                                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('M d, Y') }}</time>
                                @if($post->read_time)
                                    <span>{{ $post->read_time }} read</span>
                                @endif
                            </div>
                        </a>
                    </article>
                @endforeach
            </div>
        @endif

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/blog') }}" class="xc-btn-primary">Read All Articles</a>
            </div>
        @elseif(method_exists($posts, 'hasPages') && $posts->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>
