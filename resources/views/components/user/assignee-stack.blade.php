@props([
    'assignees' => null,
    'compact' => false,
])
@php
    $people = collect($assignees ?? []);
    $visible = $people->take(3);
    $extra = max(0, $people->count() - $visible->count());
    $names = $people->pluck('name')->filter()->values();
    $label = $names->isEmpty()
        ? 'Unassigned'
        : ($names->count() <= 2
            ? $names->implode(', ')
            : $names->take(2)->implode(', ').' +'.($names->count() - 2));
@endphp
<div {{ $attributes->class(['flex min-w-0 items-center gap-2']) }}>
    <div class="flex shrink-0 -space-x-1.5">
        @forelse ($visible as $person)
            <span
                class="flex h-6 w-6 items-center justify-center rounded-full border border-xc-darker bg-xc-blue/30 text-[10px] font-semibold text-xc-cyan"
                title="{{ $person->name }}"
            >
                {{ strtoupper(substr($person->name ?? '?', 0, 1)) }}
            </span>
        @empty
            <span class="flex h-6 w-6 items-center justify-center rounded-full border border-xc-darker bg-white/10 text-[10px] font-semibold text-white/40">?</span>
        @endforelse
        @if ($extra > 0)
            <span class="flex h-6 w-6 items-center justify-center rounded-full border border-xc-darker bg-white/10 text-[10px] font-semibold text-white/60">
                +{{ $extra }}
            </span>
        @endif
    </div>
    @unless ($compact)
        <span class="truncate text-xs text-white/55" title="{{ $names->implode(', ') }}">{{ $label }}</span>
    @endunless
</div>
