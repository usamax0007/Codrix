@extends('frontend.layout.app')
@section('content')

    <!-- Services -->
    <section class="py-20 bg-[#020C19] text-white pt-32 px-6 scroll-reveal">
        <div class="xl:max-w-[82%] max-w-[98%] mx-auto">
            <div class="text-center mb-16">
                <div
                    class="inline-block px-4 py-1 rounded-full bg-blue-600/30 text-blue-500 text-sm font-semibold uppercase tracking-wider mb-4">
                    Services
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6">Check Our <span class="text-blue-500">Services</span>
                </h2>

                <h1 class="text-4xl text-left font-bold mb-4">Comprehensive Digital Solutions</h1>
                <div class="flex flex-col md:flex-row justify-between items-center gap-6">

                    <p class="text-gray-400 max-w-xl text-lg text-left">We deliver end-to-end digital
                        services that drive growth, enhance user experience, and transform your business vision into
                        reality.</p>
                    <a href="#"
                       class="text-blue-500 font-semibold text-lg flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                        All Services →</a>
                </div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                <!-- Card 1 -->
                @foreach($services as $service)
                    <div
                        class="bg-[#141E2B] p-8 rounded-3xl border border-white/10 hover:border-blue-500/50 transition-all group hover:-translate-y-1">
                        <div class="flex justify-between items-start mb-6">
                            <div class="p-3 bg-blue-500/10 rounded-xl text-blue-500">
                                <svg class="w-10 h-10 text-blue-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                     viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M12 7h.01m3.486 1.513h.01m-6.978 0h.01M6.99 12H7m9 4h2.706a1.957 1.957 0 0 0 1.883-1.325A9 9 0 1 0 3.043 12.89 9.1 9.1 0 0 0 8.2 20.1a8.62 8.62 0 0 0 3.769.9 2.013 2.013 0 0 0 2.03-2v-.857A2.036 2.036 0 0 1 16 16Z"/>
                                </svg>
                            </div>
                            @if($service->is_popular)
                                <span
                                    class="bg-[#FF825B] text-white text-xs font-bold px-3 py-1 rounded-full uppercase">Popular</span>
                            @endif
                        </div>
                        <h3 class="text-2xl font-bold mb-4">{{ $service->title }}</h3>
                        <p class="text-gray-400 mb-6 text-sm leading-relaxed">{{ $service->description }}</p>
                        <ul class="space-y-3 mb-8 text-sm">
                            @foreach($service->items as $item)
                                <li class="flex items-center gap-2">
                                    <svg class="w-6 h-6 text-blue-600" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg"
                                         width="24" height="24" fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2"
                                              d="M8.5 11.5 11 14l4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    {{ $item->name }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="#" class="text-blue-500 font-semibold inline-flex items-center gap-2">Explore Service
                            →</a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Ready to Start -->
    <section class="xl:max-w-[82%] max-w-[98%] mx-auto py-12 px-6 scroll-reveal">
        <div class="bg-blue-500 rounded-3xl p-4 md:p-12 flex flex-col lg:flex-row items-center justify-between gap-8">

            <div class="text-white max-w-xl">
                <span class="inline-block bg-white/20 text-white text-sm font-semibold px-4 py-1.5 rounded-full mb-4">READY TO START?</span>
                <h2 class="text-3xl md:text-4xl font-bold mb-3">Transform Your Digital Presence Today</h2>
                <p class="text-blue-50 opacity-90">Partner with us to create innovative solutions that drive real
                    business
                    results. Let's build something extraordinary together.</p>
            </div>

            <div class="flex flex-col gap-4 w-full lg:w-auto">
                <div class="text-end">
                    <button
                        class="bg-white text-blue-600 font-bold xl:w-40 w-full px-4 py-3 rounded-lg hover:bg-gray-100 transition shadow-lg">
                        Get Started →
                    </button>
                </div>
                <button
                    class="bg-transparent border border-white/30 text-white font-semibold px-8 py-3 rounded-lg hover:bg-white/10 transition">
                    Schedule Consultation
                </button>
            </div>
        </div>
    </section>

@endsection
