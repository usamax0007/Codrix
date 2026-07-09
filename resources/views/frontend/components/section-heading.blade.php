@php
    $badge = $badge ?? '';
    $title = $title ?? '';
    $subtitle = $subtitle ?? '';
    $centered = $centered ?? true;
@endphp
<div class="{{ $centered ? 'text-center' : '' }} mb-12 scroll-reveal">
    @if($badge)<span class="xc-badge mb-4 inline-block">{{ $badge }}</span>@endif
    @if($title)<h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">{!! $title !!}</h2>@endif
    @if($subtitle)<p class="text-slate-400 text-lg max-w-2xl {{ $centered ? 'mx-auto' : '' }}">{{ $subtitle }}</p>@endif
</div>
