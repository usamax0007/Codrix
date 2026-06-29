@extends('frontend.layout.app')
@section('content')

    <!-- Portfolio -->
    <section class="py-16 bg-[#020C19] text-white pt-32 px-6 scroll-reveal">
        <div class="xl:max-w-[82%] max-w-[98%] mx-auto">
            <div class="text-center mb-16">
                <div class="inline-block px-4 py-2 rounded-full bg-blue-500/10 text-blue-500 text-md font-bold uppercase tracking-wider mb-4">
                    Portfolio
                </div>
                <h2 class="text-4xl md:text-5xl font-bold">Check Our <span class="text-blue-500">Portfolio</span></h2>
            </div>

            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <button class="filter-btn px-6 py-3 rounded-lg bg-[#208BF4] font-semibold text-white" data-filter="all">
                    All
                </button>
                <button class="filter-btn px-6 py-3 rounded-lg bg-[#0A1625] hover:bg-white/10 transition text-white"
                        data-filter="web">Web Development
                </button>
                <button class="filter-btn px-6 py-3 rounded-lg bg-[#0A1625] hover:bg-white/10 transition text-white"
                        data-filter="mobile">Mobile Apps
                </button>
                <button class="filter-btn px-6 py-3 rounded-lg bg-[#0A1625] hover:bg-white/10 transition text-white"
                        data-filter="branding">Branding
                </button>
                <button class="filter-btn px-6 py-3 rounded-lg bg-[#0A1625] hover:bg-white/10 transition text-white"
                        data-filter="ui-ux">UI/UX Design
                </button>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card 1 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="web">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-1.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-1.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="absolute top-4 right-4 bg-[#208BF4] text-sm font-bold px-4 py-2 rounded-md">FEATURED</span>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">WEB DEVELOPMENT</span>
                            <span class="text-gray-500">2024</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Enterprise Analytics Dashboard</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Nulla porttitor accumsan tincidunt. Vivamus
                            suscipit tortor
                            eget felis porttitor volutpat.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">React</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">TypeScript</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Node.js</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 2 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="mobile">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-2.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-2.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">MOBILE APPS</span>
                            <span class="text-gray-500">2024</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Fitness Tracking Application</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Praesent sapien massa, convallis a pellentesque
                            nec, egestas non nisi.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Flutter</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Firebase</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">ML Kit</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 3 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="branding">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-3.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-3.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="absolute top-4 right-4 bg-[#208BF4] text-sm font-bold px-4 py-2 rounded-md">Award Winner</span>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">Branding</span>
                            <span class="text-gray-500">2023</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Tech Startup Brand Identity</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Vestibulum ac diam sit amet quam vehicula
                            elementum sed sit amet dui.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Illustrator</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Figma</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">After Effects</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 4 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="mobile">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-4.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-4.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                        <span class="absolute top-4 right-4 bg-[#208BF4] text-sm font-bold px-4 py-2 rounded-md">FEATURED</span>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">MOBILE APPS</span>
                            <span class="text-gray-500">2024</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Digital Banking Solutions</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Mauris blandit aliquet elit, eget tincidunt nibh
                            pulvinar a.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">React Native</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Biometrics</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Secure API</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 5 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="ui-ux">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-5.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-5.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">UI/UX DESIGN</span>
                            <span class="text-gray-500">2024</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Cloud-Based SaaS Platform</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Curabitur non nulla sit amet nisl tempus
                            convallis quis ac lectus.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Figma</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Prototyping</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Design System</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 6 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="web">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-6.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-6.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">WEB DEVELOPMENT</span>
                            <span class="text-gray-500">2023</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Multi-Vendor E-commerce</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Donec sollicitudin molestie malesuada. Curabitur
                            arcu erat, accumsan id imperdiet et.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Vue.js</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Shopify</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">GraphQL</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 7 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="branding">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-7.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-7.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">Branding</span>
                            <span class="text-gray-500">2023</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Restaurant Chain Rebrand</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Pellentesque in ipsum id orci porta dapibus. Sed
                            porttitor lectus nibh.</p>
                        <div class="flex gap-2 mb-6 overflow-x-auto no-scrollbar scroll-smooth whitespace-nowrap pb-2">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px] flex-shrink-0">Brand Strategy</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px] flex-shrink-0">Print Design</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px] flex-shrink-0">Packaging</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 8 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300"
                     data-category="ui-ux">
                    <div class="h-64 bg-gray-300 relative">
                        <img src="{{ asset('images/portfolio-8.webp') }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-8.webp">
                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">UI/UX Design</span>
                            <span class="text-gray-500">2023</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Patient Management Portal</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Quisque velit nisi, pretium ut lacinia in,
                            elementum id enim.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Sketch</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">InVision</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">User Testing</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>


                <!-- Card 9 -->
                <div class="portfolio-item group bg-[#0C1623] rounded-2xl border border-white/10 overflow-hidden hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300 relative"
                     data-category="web">
                    <div class="h-64 bg-gray-300 relative overflow-hidden">
                        <img src="{{ asset('images/portfolio-9.webp') }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">

                        <div class="absolute inset-0 bg-blue-400/80 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center cursor-pointer"
                             data-img="images/portfolio-9.webp">

                            <div class="bg-[#0C1623] p-2 rounded-xl shadow-2xl scale-0 group-hover:scale-100 transition-transform duration-300">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                          d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="xl:p-6 p-2 bg-[#0C1623]">
                        <div class="flex justify-between font-bold text-xs mb-3">
                            <span class="text-[#208BF4]">Web Development</span>
                            <span class="text-gray-500">2024</span>
                        </div>
                        <h3 class="text-2xl font-bold mb-3">Property Listing Plateform</h3>
                        <p class="text-gray-400 text-sm font-semibold mb-4">Cras ultricies ligula sed magna dictum porta.
                            Nulla quis lorem ut libero malesuada.</p>
                        <div class="flex gap-2 mb-6">
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Next.js</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">Maps API</span>
                            <span class="px-2 py-1 bg-blue-700/10 border border-blue-800/20 text-[#208BF4] rounded text-[13px]">PostgreSQL</span>
                        </div>
                        <a href="#" class="font-semibold flex items-center gap-2 hover:-translate-y-0.5 transition-transform">View
                            Case Study →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Have a Project in mind -->
    <section class="xl:max-w-[82%] max-w-[98%] mx-auto py-12 px-6 scroll-reveal">

        <div class="bg-[#208BF4] rounded-2xl py-10 px-8 flex flex-col lg:flex-row items-center justify-between gap-8 shadow-[0_0_50px_rgba(32,139,244,0.25)] border border-white/10">

            <div class="text-white max-w-2xl">
                <h2 class="text-3xl md:text-4xl lg:text-3xl font-bold mb-4 tracking-tight">
                    Have a project in mind?
                </h2>
                <p class="text-white/90 text-base md:text-md leading-relaxed max-w-xl">
                    Let's collaborate to create something exceptional. Our team is ready to bring your digital vision to
                    life.
                </p>
            </div>

            <div class="w-full lg:w-auto flex justify-end">
                <button class="w-full sm:w-auto bg-[#0C1623] text-[#208BF4] font-bold px-8 py-4 rounded-xl border border-white/5 hover:bg-[#141E2B] transition-all duration-300 shadow-xl text-md tracking-wide hover:-translate-y-0.5">
                    Start Your Project
                </button>
            </div>
        </div>
    </section>

@endsection
