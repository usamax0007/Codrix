@props([
    'status',
])
@php
    $styles = match ($status->value) {
        'open' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
        'present' => 'bg-xc-cyan/15 text-xc-cyan border-xc-cyan/30',
        'late' => 'bg-red-500/15 text-red-300 border-red-500/30',
        'absent' => 'bg-white/10 text-white/55 border-white/15',
        default => 'bg-white/10 text-white/70 border-white/15',
    };
@endphp
<span {{ $attributes->class(['inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-medium', $styles]) }}>
    {{ $status->getLabel() }}
</span>
