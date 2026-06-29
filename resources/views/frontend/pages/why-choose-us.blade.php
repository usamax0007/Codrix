@extends('frontend.layout.app')
@section('content')

    <!-- why Us -->
    <section class="py-20 bg-[#0B1726] text-white pt-32 px-6 scroll-reveal">
        <div class="xl:max-w-[82%] max-w-[98%] mx-auto">

            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 rounded-full bg-blue-500/10 text-blue-500 text-[12px] font-bold uppercase tracking-wider mb-4">
                    Why Us
                </div>
                <h2 class="text-4xl md:text-4xl font-bold">Why <span class="text-blue-500">Choose Us</span></h2>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <div class="space-y-8">
                    <div>
                    <span class="inline-block px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[12px] font-bold mb-4">
                        Why Choose Us
                    </span>
                        <h3 class="text-3xl md:text-[42px] font-bold mb-4 leading-tight">
                            Partner with a Team That Delivers Real Results
                        </h3>
                        <p class="text-gray-400 text-base md:text-lg leading-relaxed">
                            We don't just create digital experiences—we build solutions that drive measurable growth.
                            Our data-driven approach combines creativity with performance to help your business thrive in
                            the digital landscape.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pt-6 border-t border-white/5">
                        <div>
                            <h4 class="text-4xl md:text-5xl font-bold text-blue-500 count-up mb-2" data-target="150">0</h4>
                            <p class="text-gray-400 text-xs md:text-sm font-semibold">Projects Delivered</p>
                        </div>
                        <div>
                            <h4 class="text-4xl md:text-5xl font-bold text-blue-500 count-up mb-2" data-target="98">0</h4>
                            <p class="text-gray-400 text-xs md:text-sm font-semibold">% Client Retention</p>
                        </div>
                        <div>
                            <h4 class="text-4xl md:text-5xl font-bold text-blue-500 count-up mb-2" data-target="250">0</h4>
                            <p class="text-gray-400 text-xs md:text-sm font-semibold">% Avg ROI Increase</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center lg:justify-end">
                    <div class="relative max-w-2xl w-full">
                        <img src="{{ asset('images/why-choose-us.webp') }}" alt="Why Choose Us Illustration"
                             class="w-full h-auto object-contain">
                    </div>
                </div>
            </div>
        </div>


        <div class="bg-[#0B1726] mt-16">
            <div class="xl:max-w-[82%] max-w-[98%] mx-auto grid md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="bg-[#101C2D] p-8 rounded-2xl border border-gray-700 relative flex flex-col items-start max-w-sm transition-all duration-300 ease-in-out hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Fast Turnaround</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Quis nostrud exercitation ullamco laboris nisi ut aliquip
                        ex
                        ea commodo consequat duis aute irure dolor.</p>
                    <a href="#"
                       class="text-blue-500 font-semibold flex items-center gap-2 hover:translate-x-2 transition-transform">Learn
                        more →</a>
                </div>

                <div class="bg-[#081C32] p-8 rounded-2xl border border-blue-500 relative flex flex-col items-start max-w-sm transition-all duration-300 ease-in-out hover:-translate-y-2">

                    <div class="w-full flex justify-between items-start mb-6">
                        <div class="w-16 h-16 bg-[#208BF4] rounded-2xl flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <span class="bg-[#208BF4] text-white text-[12px] font-bold px-4 py-1 rounded-full tracking-wide">
                    Most Popular
                </span>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-3 tracking-tight">Data-Driven Strategy</h3>

                    <p class="text-gray-400 text-sm font-medium leading-relaxed mb-6">
                        Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium
                        totam.
                    </p>

                    <a href="#"
                       class="text-[#208BF4] font-bold flex items-center gap-2 hover:translate-x-1 transition-transform">
                        Learn more <span class="text-base">→</span>
                    </a>
                </div>

                <div class="bg-[#101C2D] p-8 rounded-2xl border border-gray-700 relative flex flex-col items-start max-w-sm transition-all duration-300 ease-in-out hover:-translate-y-2">
                    <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Proven Expertise</h3>
                    <p class="text-gray-400 mb-6 leading-relaxed">Nemo enim ipsam voluptatem quia voluptas sit aspernatur
                        aut
                        odit aut fugit sed quia consequuntur magni.</p>
                    <a href="#"
                       class="text-blue-500 font-semibold flex items-center gap-2 hover:translate-x-2 transition-transform">Learn
                        more →</a>
                </div>
            </div>
        </div>

        <div class="py-20 bg-[#0B1727] text-white">
            <div class="xl:max-w-[82%] max-w-[98%] mx-auto grid lg:grid-cols-2 gap-10 items-start">

                <div class="space-y-4 mt-10 order-2 lg:order-1">
                    <div class="bg-[#101C2D] p-6 rounded-2xl border border-gray-700 flex items-center gap-6">
                        <span class="w-16 h-16 flex items-center justify-center bg-[#437EF5] text-white font-bold text-2xl rounded-xl">01</span>
                        <div>
                            <h3 class="text-xl font-bold">Discovery</h3>
                            <p class="text-gray-400 font-semibold text-sm">Understanding your goals</p>
                        </div>
                    </div>
                    <div class="bg-[#101C2D] p-6 rounded-2xl border border-gray-700 flex items-center gap-6">
                        <span class="w-16 h-16 flex items-center justify-center bg-[#437EF5] text-white font-bold text-2xl rounded-xl">02</span>
                        <div>
                            <h3 class="text-xl font-bold">Strategy</h3>
                            <p class="text-gray-400 font-semibold text-sm">Planning the approach</p>
                        </div>
                    </div>
                    <div class="bg-[#101C2D] p-6 rounded-2xl border border-gray-700 flex items-center gap-6">
                        <span class="w-16 h-16 flex items-center justify-center bg-[#437EF5] text-white font-bold text-2xl rounded-xl">03</span>
                        <div>
                            <h3 class="text-xl font-bold">Execution</h3>
                            <p class="text-gray-400 font-semibold text-sm">Bringing ideas to life</p>
                        </div>
                    </div>
                    <div class="bg-[#101C2D] p-6 rounded-2xl border border-gray-700 flex items-center gap-6">
                        <span class="w-16 h-16 flex items-center justify-center bg-[#437EF5] text-white font-bold text-2xl rounded-xl">04</span>
                        <div>
                            <h3 class="text-xl font-bold">Optimization</h3>
                            <p class="text-gray-400 font-semibold text-sm">Continuous improvement</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-10 order-1 lg:order-2">
                    <h2 class="text-4xl md:text-[42px] font-bold mb-6">Our Core Capabilities</h2>
                    <p class="text-gray-300 mb-10 text-md leading-relaxed">At vero eos et accusamus et iusto odio
                        dignissimos
                        ducimus
                        qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores.</p>

                    <div class="space-y-6 mb-10">
                        <div class="flex gap-4">
                    <span class="text-[#437EF5] mt-1 text-xl">
                        <svg class="w-8 h-8 text-[#208BF4]" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                             viewBox="0 0 24 24"><path fill-rule="evenodd"
                                                       d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                                       clip-rule="evenodd"/>
                        </svg>
                    </span>
                            <div>
                                <h4 class="font-bold text-lg">Strategic Planning & Consulting</h4>
                                <p class="text-gray-400 text-md">Temporibus autem quibusdam et aut officiis debitis aut
                                    rerum
                                    necessitatibus saepe eveniet ut et voluptates.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                    <span class="text-[#437EF5] mt-1 text-xl">
                        <svg class="w-8 h-8 text-[#208BF4]" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                             viewBox="0 0 24 24"><path fill-rule="evenodd"
                                                       d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                                       clip-rule="evenodd"/>
                        </svg>
                    </span>
                            <div>
                                <h4 class="font-bold text-lg">Custom Development Solutions</h4>
                                <p class="text-gray-400 text-md">Nam libero tempore cum soluta nobis est eligendi optio
                                    cumque
                                    nihil impedit quo minus id quod maxime.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                    <span class="text-[#437EF5] mt-1 text-xl">
                        <svg class="w-8 h-8 text-[#208BF4]" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                             viewBox="0 0 24 24"><path fill-rule="evenodd"
                                                       d="M2 12C2 6.477 6.477 2 12 2s10 4.477 10 10-4.477 10-10 10S2 17.523 2 12Zm13.707-1.293a1 1 0 0 0-1.414-1.414L11 12.586l-1.793-1.793a1 1 0 0 0-1.414 1.414l2.5 2.5a1 1 0 0 0 1.414 0l4-4Z"
                                                       clip-rule="evenodd"/>
                        </svg>
                    </span>
                            <div>
                                <h4 class="font-bold text-lg">Ongoing Support & Optimization</h4>
                                <p class="text-gray-400 text-md">Itaque earum rerum hic tenetur a sapiente delectus ut aut
                                    reiciendis voluptatibus maiores alias.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-4">
                        <button class="bg-[#208BF4] hover:bg-[#208BF4] transition px-8 py-4 rounded-xl font-bold w-full md:w-48 whitespace-nowrap">
                            Start Your
                            Project
                        </button>
                        <button class="border-2 border-[#208BF4] hover:bg-[#208BF4] transition px-8 py-4 rounded-xl text-[#208BF4] hover:text-white font-bold w-full md:w-48 whitespace-nowrap">
                            View
                            Case Studies
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
