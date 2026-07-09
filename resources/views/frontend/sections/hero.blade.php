<section class="relative pt-28 pb-20 lg:pt-36 lg:pb-28 overflow-hidden" aria-label="Hero">
    <div class="xc-glow bg-xc-cyan top-0 right-0"></div>
    <div class="xc-glow bg-xc-blue bottom-0 left-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6 scroll-reveal">
                <span class="xc-badge">✨ Premium Software Agency</span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                    Build <span class="xc-gradient-text">World-Class Software</span> That Drives Growth
                </h1>
                <p class="text-slate-400 text-lg md:text-xl leading-relaxed max-w-xl">
                    XCodrix is a software development agency specializing in AI, SaaS, Laravel, Vue.js, mobile apps, and Twilio communication systems for startups and enterprises worldwide.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    <a href="{{ url('/contact') }}" class="xc-btn-primary">Book a Call</a>
                    <a href="{{ url('/contact') }}" class="xc-btn-outline">Get a Quote</a>
                </div>
                <p class="text-sm text-slate-500">Free consultation · Proposal within 48 hours · No commitment</p>
            </div>
            <div class="relative scroll-reveal">
                <img src="{{ asset('images/hero-img.webp') }}"
                     alt="XCodrix software development team building custom applications"
                     class="w-full rounded-2xl shadow-2xl shadow-xc-blue/10"
                     width="600" height="500" loading="eager">
            </div>
        </div>
    </div>
</section>
