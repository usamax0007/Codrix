<section class="py-16 bg-xc-card/30" aria-label="Company statistics">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach(config('xcodrix.stats') as $stat)
                <div class="text-center scroll-reveal">
                    <div class="stat-value mb-2" data-count="{{ (int) filter_var($stat['value'], FILTER_SANITIZE_NUMBER_INT) }}" data-suffix="{{ str_contains($stat['value'], '+') ? '+' : (str_contains($stat['value'], '%') ? '%' : '') }}">0</div>
                    <p class="text-slate-400 text-sm font-medium">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
