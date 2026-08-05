@props(['services', 'preview' => true, 'limit' => 6])

<section class="py-20 bg-xc-dark/40" @if($preview) id="services" @endif aria-labelledby="services-heading">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($preview)
            @include('frontend.components.section-heading', [
                'badge' => $serviceSettings->section_badge,
                'title' => $serviceSettings->section_title,
                'subtitle' => $serviceSettings->section_subtitle,
            ])
        @endif

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach(($preview ? $services->take($limit) : $services) as $service)
                <article class="xc-card scroll-reveal" id="{{ $service->slug }}">
                    <div class="flex justify-between items-start mb-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-xc-cyan/20 to-xc-blue/20 flex items-center justify-center text-xc-cyan">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        @if($service->is_popular)
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full bg-orange-500/20 text-orange-400">Popular</span>
                        @endif
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">{{ $service->title }}</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-4">{{ $service->summary }}</p>
                    @unless($preview)
                        <div class="space-y-4 mt-4 border-t border-white/5 pt-4">
                            @if($service->what)
                                <div>
                                    <h4 class="text-sm font-semibold text-xc-cyan mb-1">What it is</h4>
                                    <p class="text-slate-400 text-sm">{{ $service->what }}</p>
                                </div>
                            @endif
                            @if(!empty($service->benefits))
                                <div>
                                    <h4 class="text-sm font-semibold text-xc-cyan mb-1">Business benefits</h4>
                                    <ul class="text-slate-400 text-sm space-y-1">
                                        @foreach($service->benefits as $benefit)
                                            <li class="flex items-start gap-2"><span class="text-xc-cyan mt-0.5">•</span>{{ is_array($benefit) ? ($benefit['benefit'] ?? reset($benefit)) : $benefit }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if(!empty($service->technologies))
                                <div>
                                    <h4 class="text-sm font-semibold text-xc-cyan mb-1">Technologies</h4>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($service->technologies as $tech)
                                            <span class="text-xs px-2 py-1 rounded bg-white/5 text-slate-300">{{ $tech }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($service->why)
                                <p class="text-slate-400 text-sm italic border-t border-white/5 pt-3">{{ $service->why }}</p>
                            @endif
                        </div>
                    @endunless
                </article>
            @endforeach
        </div>

        @if($preview)
            <div class="text-center mt-10 scroll-reveal">
                <a href="{{ url('/services') }}" class="xc-btn-primary">View All Services</a>
            </div>
        @endif
    </div>
</section>
