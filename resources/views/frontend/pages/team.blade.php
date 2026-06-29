@extends('frontend.layout.app')
@section('content')

    <!-- Team -->
    <section class="py-10 bg-[#0B1726] text-white pt-32 px-6 scroll-reveal">
        <div class="xl:max-w-[82%] max-w-[98%] mx-auto">

            <div class="text-center mb-16">
                <span class="inline-block px-4 py-1.5 rounded-full bg-blue-800/30 text-blue-500 text-sm font-bold uppercase tracking-widest mb-4">Team</span>
                <h2 class="text-4xl md:text-[40px] font-bold">Check Our <span class="text-blue-500">Team</span></h2>
            </div>


            <div class="flex flex-col md:flex-row justify-between items-end mb-12">

                <div class="max-w-xl">
                    <h2 class="text-4xl font-bold mb-4"><span
                            class="border-b-4 border-blue-500 pb-2 inline-block">Our</span> Professional Team</h2>
                    <p class="text-gray-300 text-md">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                        Ut elit tellus luctus nec ullamcorper mattis pulvinar dapibus leo.
                    </p>
                </div>

                <div class="flex gap-4 mt-6 md:mt-0">
                    <button onclick="movePrev()"
                            class="p-3 rounded-full border border-blue-500 transition-all text-gray-400 hover:text-white">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>
                    <button onclick="moveNext()"
                            class="p-3 rounded-full border border-blue-500 transition-all text-gray-400 hover:text-white">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="overflow-hidden relative justify-start items-start">
                <div id="team-slider" class="flex transition-transform duration-700 ease-in-out">

                    <!-- Card 1 -->
                    <div class="min-w-[100%] sm:min-w-[50%] lg:min-w-[25%] px-4">
                        <div class="group bg-[#101C2D] rounded-2xl overflow-hidden flex flex-col hover:border hover:border-blue-500/50 transition-all duration-300">

                            <div class="relative overflow-hidden">
                                <img src="{{ asset('images/team-1.webp') }}" alt="Sophia Reynolds"
                                     class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-black/40 flex items-end pb-4 justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                                        </svg>
                                    </a>


                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                                  clip-rule="evenodd"/>
                                            <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="font-bold text-xl">Sophia Reynolds</h3>
                                <p class="text-blue-500 font-semibold mb-3">Product Designer</p>
                                <p class="text-gray-300 font-semibold text-sm">Duis aute irure dolor in reprehenderit...</p>
                            </div>
                        </div>
                    </div>


                    <!-- Card 2 -->
                    <div class="min-w-[100%] sm:min-w-[50%] lg:min-w-[25%] px-4">
                        <div class="group bg-[#101C2D] rounded-2xl overflow-hidden flex flex-col hover:border hover:border-blue-500/50 transition-all duration-300">

                            <div class="relative overflow-hidden">
                                <img src="{{ asset('images/team-2.webp') }}" alt="Daniel Chen"
                                     class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-black/40 flex items-end pb-4 justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                                        </svg>
                                    </a>


                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                                  clip-rule="evenodd"/>
                                            <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="font-bold text-xl">Daniel Chen</h3>
                                <p class="text-blue-500 font-semibold mb-3">Marketing Specialist</p>
                                <p class="text-gray-300 font-semibold text-sm">Duis aute irure dolor in reprehenderit...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="min-w-[100%] sm:min-w-[50%] lg:min-w-[25%] px-4">
                        <div class="group bg-[#101C2D] rounded-2xl overflow-hidden flex flex-col hover:border hover:border-blue-500/50 transition-all duration-300">

                            <div class="relative overflow-hidden">
                                <img src="{{ asset('images/team-3.webp') }}" alt="Olivia Thompson"
                                     class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-black/40 flex items-end pb-4 justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                                        </svg>
                                    </a>


                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                                  clip-rule="evenodd"/>
                                            <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="font-bold text-xl">Olivia Thompson</h3>
                                <p class="text-blue-500 font-semibold mb-3">Lead Developer</p>
                                <p class="text-gray-300 font-semibold text-sm">Duis aute irure dolor in reprehenderit...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->
                    <div class="min-w-[100%] sm:min-w-[50%] lg:min-w-[25%] px-4">
                        <div class="group bg-[#101C2D] rounded-2xl overflow-hidden flex flex-col hover:border hover:border-blue-500/50 transition-all duration-300">

                            <div class="relative overflow-hidden">
                                <img src="{{ asset('images/team-4.webp') }}" alt="Jason Parker"
                                     class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-black/40 flex items-end pb-4 justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                                        </svg>
                                    </a>


                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                                  clip-rule="evenodd"/>
                                            <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="font-bold text-xl">Jason Parker</h3>
                                <p class="text-blue-500 font-semibold mb-3">UI/UX Designer</p>
                                <p class="text-gray-300 font-semibold text-sm">Duis aute irure dolor in reprehenderit...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 5 -->
                    <div class="min-w-[100%] sm:min-w-[50%] lg:min-w-[25%] px-4">
                        <div class="group bg-[#101C2D] rounded-2xl overflow-hidden flex flex-col hover:border hover:border-blue-500/50 transition-all duration-300">

                            <div class="relative overflow-hidden">
                                <img src="{{ asset('images/team-5.webp') }}" alt="Emily Rodriguez"
                                     class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-black/40 flex items-end pb-4 justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                                        </svg>
                                    </a>


                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                                  clip-rule="evenodd"/>
                                            <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="font-bold text-xl">Emily Rodriguez</h3>
                                <p class="text-blue-500 font-semibold mb-3">Project Manager</p>
                                <p class="text-gray-300 font-semibold text-sm">Duis aute irure dolor in reprehenderit...</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card 6 -->
                    <div class="min-w-[100%] sm:min-w-[50%] lg:min-w-[25%] px-4">
                        <div class="group bg-[#101C2D] rounded-2xl overflow-hidden flex flex-col hover:border hover:border-blue-500/50 transition-all duration-300">

                            <div class="relative overflow-hidden">
                                <img src="{{ asset('images/team-6.webp') }}" alt="Marcus Wilson"
                                     class="w-full h-60 object-cover transition-transform duration-500 group-hover:scale-105">

                                <div class="absolute inset-0 bg-black/40 flex items-end pb-4 justify-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-5 h-5 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path d="M13.795 10.533 20.68 2h-3.073l-5.255 6.517L7.69 2H1l7.806 10.91L1.47 22h3.074l5.705-7.07L15.31 22H22l-8.205-11.467Zm-2.38 2.95L9.97 11.464 4.36 3.627h2.31l4.528 6.317 1.443 2.02 6.018 8.409h-2.31l-4.934-6.89Z"/>
                                        </svg>
                                    </a>


                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                             viewBox="0 0 24 24">
                                            <path fill="currentColor" fill-rule="evenodd"
                                                  d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a href="#"
                                       class="bg-white p-2 rounded-full text-blue-500 hover:bg-blue-500 hover:text-white transition-all">
                                        <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                             viewBox="0 0 24 24">
                                            <path fill-rule="evenodd"
                                                  d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z"
                                                  clip-rule="evenodd"/>
                                            <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>

                            <div class="p-6 flex-1">
                                <h3 class="font-bold text-xl">Marcus Wilson</h3>
                                <p class="text-blue-500 font-semibold mb-3">Chief Technology Officer</p>
                                <p class="text-gray-300 font-semibold text-sm">Duis aute irure dolor in reprehenderit...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
