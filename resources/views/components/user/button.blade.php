@props([
    'variant' => 'primary',
    'size' => 'md',
    'type' => 'button',
    'href' => null,
])
@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition focus:outline-none focus-visible:ring-2 focus-visible:ring-xc-cyan/60 disabled:opacity-50 disabled:pointer-events-none';

    $sizes = match ($size) {
        'sm' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2.5 text-sm',
    };

    $variants = match ($variant) {
        'outline' => 'border border-white/20 text-white hover:border-xc-cyan hover:text-xc-cyan bg-transparent',
        'ghost' => 'text-white/70 hover:text-white hover:bg-white/5',
        'danger' => 'text-white bg-red-600 hover:bg-red-500 shadow-lg shadow-red-900/30',
        default => 'text-white bg-gradient-to-r from-xc-cyan to-xc-blue shadow-lg shadow-xc-blue/20 hover:opacity-95',
    };

    $classes = trim("$base $sizes $variants");
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->class([$classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->class([$classes]) }}>
        {{ $slot }}
    </button>
@endif
