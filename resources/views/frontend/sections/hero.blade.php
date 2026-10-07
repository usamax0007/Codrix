<section class="relative pt-28 pb-20 lg:pt-36 lg:pb-28 overflow-hidden" aria-label="Hero">
    <div class="xc-glow bg-xc-cyan top-0 right-0"></div>
    <div class="xc-glow bg-xc-blue bottom-0 left-0"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="space-y-6 scroll-reveal">
                <span class="xc-badge">AI voice agents and VoIP for startups and SMBs</span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight">
                    Never miss another <span class="xc-gradient-text">customer call</span>
                </h1>
                <p class="text-slate-400 text-lg md:text-xl leading-relaxed max-w-xl">
                    Xcodrix builds AI receptionists, Twilio and VoIP call systems, and WhatsApp automation that answer, qualify, book and route every call. Built for startups and small businesses in the US, UK, Canada and the Gulf.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 pt-2">
                    @include('frontend.components.book-a-call-button', ['label' => 'Book a 20-min call'])
                    <a href="#services" class="xc-btn-outline">See what we build</a>
                </div>
                <p class="text-sm text-slate-400">Fixed-price proposal in 48 hours · Weekly demos · You own the code</p>
            </div>
            <div class="relative scroll-reveal">
                <div class="aspect-square w-full max-w-lg mx-auto relative">
                    <svg viewBox="0 0 400 400" class="w-full h-full" aria-hidden="true">
                        <defs>
                            <linearGradient id="waveGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" style="stop-color:#22D3EE;stop-opacity:0.8" />
                                <stop offset="100%" style="stop-color:#3B82F6;stop-opacity:0.4" />
                            </linearGradient>
                        </defs>
                        <g transform="translate(200,200)">
                            @for ($i = 0; $i < 12; $i++)
                                <rect x="-4" y="-60" width="8" height="{{ 40 + ($i % 3) * 30 }}" 
                                      fill="url(#waveGradient)" 
                                      transform="rotate({{ $i * 30 }})" 
                                      rx="4"
                                      opacity="{{ 0.3 + ($i % 3) * 0.3 }}">
                                    <animate attributeName="height" 
                                             values="{{ 40 + ($i % 3) * 30 }};{{ 60 + ($i % 3) * 20 }};{{ 40 + ($i % 3) * 30 }}" 
                                             dur="{{ 1.5 + ($i % 3) * 0.5 }}s" 
                                             repeatCount="indefinite" />
                                </rect>
                            @endfor
                            <circle cx="0" cy="0" r="30" fill="#FBBF24" opacity="0.6">
                                <animate attributeName="r" values="30;35;30" dur="2s" repeatCount="indefinite" />
                            </circle>
                        </g>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>
