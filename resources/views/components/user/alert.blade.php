@props([
    'type' => 'info',
])
@php
    $styles = match ($type) {
        'success' => 'border-xc-cyan/40 bg-xc-cyan/10 text-xc-cyan',
        'error' => 'border-red-500/40 bg-red-500/10 text-red-300',
        default => 'border-xc-blue/40 bg-xc-blue/10 text-blue-200',
    };
@endphp
<div {{ $attributes->class(['rounded-xl border px-4 py-3 text-sm', $styles]) }} role="alert">
    {{ $slot }}
</div>
