@php
    $title = $title ?? 'Ready to Build Something Great?';
    $subtitle = $subtitle ?? 'Book a free consultation and get a tailored proposal within 48 hours.';
@endphp
<section class="py-16 px-4 scroll-reveal" aria-label="Call to action">
    <div class="max-w-4xl mx-auto xc-cta-banner p-10 md:p-14 text-center">
        <h2 class="text-2xl md:text-3xl font-bold text-white mb-3">{{ $title }}</h2>
        <p class="text-slate-400 mb-8 max-w-xl mx-auto">{{ $subtitle }}</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/contact') }}" class="xc-btn-primary">Book a Call</a>
            <a href="{{ url('/contact') }}" class="xc-btn-outline">Get a Quote</a>
        </div>
    </div>
</section>
