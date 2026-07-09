<section class="py-12 border-y border-white/5 bg-xc-dark/50" aria-label="Trusted by">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-slate-500 uppercase tracking-wider mb-8">Trusted by innovative companies worldwide</p>
        <div class="flex flex-wrap justify-center items-center gap-x-12 gap-y-4">
            @foreach(config('xcodrix.clients') as $client)
                <span class="client-logo">{{ $client }}</span>
            @endforeach
        </div>
    </div>
</section>
