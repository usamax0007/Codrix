@props([
    'href',
    'active' => false,
])
<a
    href="{{ $href }}"
    {{ $attributes->class([
        'flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition w-full',
        'bg-gradient-to-r from-xc-cyan/20 to-xc-blue/10 text-white border border-xc-cyan/20' => $active,
        'text-white/65 hover:text-white hover:bg-white/5 border border-transparent' => ! $active,
    ]) }}
    @if ($active) aria-current="page" @endif
>
    {{ $slot }}
</a>
