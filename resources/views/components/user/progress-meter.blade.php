@props([
    'progress',
    'label' => null,
    'emptyLabel' => 'No subtasks',
    'showAscii' => false,
    'showCounts' => true,
    'countNoun' => 'completed',
    'size' => 'sm',
])
@php
    /** @var array{total: int, completed: int, remaining: int, percent: int} $progress */
    $barHeight = $size === 'lg' ? 'h-3' : ($size === 'md' ? 'h-2' : 'h-1.5');
    $service = app(\App\Services\Progress\ProgressService::class);
@endphp
<div {{ $attributes->class(['space-y-1.5']) }}>
    @if ($label)
        <p class="text-[11px] font-semibold uppercase tracking-wider text-white/40">{{ $label }}</p>
    @endif

    @if ($progress['total'] === 0)
        <p class="text-xs text-white/40">{{ $emptyLabel }}</p>
    @else
        <div class="flex items-center justify-between gap-2 text-[11px] text-white/45">
            @if ($showCounts)
                <span>{{ $progress['completed'] }} / {{ $progress['total'] }} {{ $countNoun }}</span>
            @else
                <span class="sr-only">Progress</span>
            @endif
            <span>{{ $progress['percent'] }}%</span>
        </div>
        <div class="{{ $barHeight }} overflow-hidden rounded-full bg-white/10">
            <div class="h-full rounded-full bg-gradient-to-r from-xc-cyan to-xc-blue transition-all" style="width: {{ $progress['percent'] }}%"></div>
        </div>
        @if ($showAscii)
            <p class="font-mono text-xs tracking-wide text-xc-cyan/85">
                {{ $service->asciiBar($progress['percent']) }} {{ $progress['percent'] }}%
            </p>
        @endif
    @endif
</div>
