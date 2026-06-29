<header>
    <nav class="fixed w-full z-50 transition-all duration-300 bg-[#0C1623] border-b border-gray-50 border-opacity-20"
         id="navbar">

        <div class="mx-auto xl:max-w-[82%] max-w-[93%] py-4 flex justify-between items-center">

            <a href="#" class="md:text-4xl text-3xl font-bold">Forma<span class="text-blue-500">.</span></a>

            <ul class="hidden lg:flex xl:space-x-8 lg:space-x-4 text-[#D8E4F0] font-medium text-md">
                <li>
                    <a href="{{ url('/') }}"
                       class="group relative transition-all duration-300 {{ request()->is('/') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Home
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('/') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/about') }}"
                       class="group relative transition-all duration-300 {{ request()->is('about') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        About
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('about') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/services') }}"
                       class="group relative transition-all duration-300 {{ request()->is('services') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Services
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('services') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/portfolio') }}"
                       class="group relative transition-all duration-300 {{ request()->is('portfolio') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Portfolio
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('portfolio') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>


                <li>
                    <a href="{{ url('/why-choose-us') }}"
                       class="group relative transition-all duration-300 {{ request()->is('why-choose-us') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Why Choose Us
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('why-choose-us') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>

                <li>
                    <a href="{{ url('/testimonials') }}"
                       class="group relative transition-all duration-300 {{ request()->is('testimonials') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Testimonials
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('testimonials') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>


                <li>
                    <a href="{{ url('/team') }}"
                       class="group relative transition-all duration-300 {{ request()->is('team') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Team
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('team') ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                </li>


                <li>
                    <a href="{{ url('/contact') }}"
                       class="group relative transition-all duration-300 {{ request()->is('contact') ? 'text-white font-bold' : 'text-gray-300 hover:text-white' }}">
                        Contact
                        <span class="absolute left-0 -bottom-1 h-0.5 bg-blue-500 transition-all duration-500
                        {{ request()->is('contact') ? 'w-full' : 'w-0 group-hover:w-full' }}">

                        </span>
                    </a>
                </li>
            </ul>

            <div class="flex items-center justify-between gap-4">
                <a href="#"
                   class="bg-[#208BF4] hover:bg-blue-600 px-5 py-1.5 rounded-md justify-end transition text-[14px]">Get
                    Started</a>

                <button id="mobile-menu-btn" class="lg:hidden text-white text-2xl">
                    ☰
                </button>
            </div>

            <div id="mobile-menu" class="fixed inset-0 bg-[#0C1623] z-[60] p-6 hidden">
                <div class="h-full w-full rounded-xl p-6 mt-6 overflow-y-auto">
                    <div class="flex justify-between items-center mb-10">
                        <span class="text-2xl font-bold">Forma.</span>
                        <button id="close-menu-btn" class="text-white text-3xl">✕</button>
                    </div>
                    <ul class="space-y-6 text-xl">
                        <li>
                            <a href="{{ url('/') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('/') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/about') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('about') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                About
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/services') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('services') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Services
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/portfolio') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('portfolio') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Portfolio
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/why-choose-us') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('why-choose-us') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Why Choose Us
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/testimonials') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('testimonials') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Testimonials
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/team') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('team') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Team
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/contact') }}"
                               class="inline-block pb-1 transition-all duration-300 {{ request()->is('contact') ? 'text-white font-bold border-b-2 border-blue-500' : 'text-gray-300 hover:text-white' }}">
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </nav>
</header>
