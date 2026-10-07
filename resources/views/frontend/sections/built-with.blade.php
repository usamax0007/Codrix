<section class="py-12 border-y border-white/5 bg-xc-dark/50" aria-label="Built with">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="space-y-8">
            @if(config('site.upwork.show_badge') && config('site.upwork.profile_url'))
                <div class="text-center">
                    <a href="{{ config('site.upwork.profile_url') }}" 
                       target="_blank" 
                       rel="noopener" 
                       class="inline-flex items-center gap-2 px-4 py-2 bg-xc-card/50 rounded-lg border border-white/10 hover:border-xc-cyan/50 transition-colors text-sm text-slate-300">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M18.561 13.158c-1.102 0-2.135-.467-3.074-1.227l.228-1.076.008-.042c.207-1.143.849-3.06 2.839-3.06 1.492 0 2.703 1.212 2.703 2.703-.001 1.489-1.212 2.702-2.704 2.702zm0-8.14c-2.539 0-4.51 1.649-5.31 4.366-1.22-1.834-2.148-4.036-2.687-5.892H7.828v7.112c-.002 1.406-1.141 2.546-2.547 2.548-1.405-.002-2.543-1.143-2.545-2.548V3.492H0v7.112c0 2.914 2.37 5.303 5.281 5.303 2.913 0 5.283-2.389 5.283-5.303v-1.19c.529 1.107 1.182 2.229 1.974 3.221l-1.673 7.873h2.797l1.213-5.71c1.063.679 2.285 1.109 3.686 1.109 3 0 5.439-2.452 5.439-5.45 0-3-2.439-5.439-5.439-5.439z"/>
                        </svg>
                        <span>Top Rated on Upwork</span>
                    </a>
                </div>
            @endif
            
            <div>
                <p class="text-center text-sm text-slate-400 uppercase tracking-wider mb-6">Built with</p>
                <div class="flex flex-wrap justify-center items-center gap-x-8 gap-y-4">
                    @foreach(config('site.tools') as $tool)
                        <a href="{{ $tool['url'] }}" 
                           target="_blank" 
                           rel="noopener"
                           class="text-slate-400 hover:text-xc-cyan transition-colors text-sm font-medium"
                           title="{{ $tool['name'] }}">{{ $tool['name'] }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
