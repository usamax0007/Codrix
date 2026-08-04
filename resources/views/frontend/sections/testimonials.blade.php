@props(['testimonials', 'preview' => true, 'limit' => 2])

<section class="py-20 bg-xc-dark/40" aria-labelledby="testimonials-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => $testimonialSettings->section_badge ?: 'Testimonials',
                'title' => $testimonialSettings->section_title ?: "What Our <span class='xc-gradient-text'>Clients Say</span>",
                'subtitle' => $testimonialSettings->section_subtitle ?: 'Trusted by startups and enterprises worldwide.',
            ])
        @endif

        <div class="grid md:grid-cols-2 gap-6">
            @foreach(($preview ? $testimonials->take($limit) : $testimonials) as $t)
                <blockquote class="xc-card scroll-reveal">
                    <div class="flex items-center gap-4 mb-4">
                        <img src="{{ $t->imageUrl() }}" alt="{{ $t->name }}" class="w-12 h-12 rounded-full object-cover" width="48" height="48" loading="lazy">
                        <div>
                            <cite class="font-semibold text-white not-italic">{{ $t->name }}</cite>
                            @if($t->role)
                                <p class="text-sm text-slate-500">{{ $t->role }}</p>
                            @endif
                        </div>
                    </div>
                    <p class="text-slate-300 leading-relaxed">"{{ $t->text }}"</p>
                </blockquote>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/testimonials') }}" class="xc-btn-link">Read more testimonials →</a>
            </div>
        @endif
    </div>
</section>
