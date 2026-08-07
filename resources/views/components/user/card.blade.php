@props([
    'title' => null,
    'description' => null,
])
<section {{ $attributes->class(['rounded-2xl border border-white/10 bg-xc-card/80 p-6 sm:p-8 shadow-xl shadow-black/20']) }}>
    @if ($title || $description || isset($header))
        <header class="mb-5">
            @isset($header)
                {{ $header }}
            @else
                @if ($title)
                    <h2 class="text-lg font-semibold tracking-tight">{{ $title }}</h2>
                @endif
                @if ($description)
                    <p class="mt-1 text-sm text-white/60">{{ $description }}</p>
                @endif
            @endisset
        </header>
    @endif

    {{ $slot }}
</section>
