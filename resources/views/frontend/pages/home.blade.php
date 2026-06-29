@extends('frontend.layout.app')
@section('content')

<!-- New Features -->
<section class="pt-32 pb-20 px-6 min-h-screen flex items-center scroll-reveal">
    <div class="xl:max-w-[82%] max-w-[98%] mx-auto grid lg:grid-cols-2 gap-12 items-center">
        <div class="space-y-6 text-center items-center justify-center lg:text-start lg:items-start lg:justify-start">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full font-bold border border-blue-500/30 text-[#208BF4] text-sm bg-blue-500/10">
                    ✨ New Feature Launch
                </span>
            <h1 class="xl:text-5xl text-5xl font-bold leading-tight">{{ $home->title }}</h1>
            <p class="text-[#BCC1C5] xl:text-lg lg:text-md md:text-lg">
                {{ $home->description }}
            </p>
            <div class="flex flex-col md:flex-row gap-4 items-center justify-center lg:justify-start w-full">

                <button class="bg-[#208BF4] whitespace-nowrap md:w-auto hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 px-8 py-3 rounded-lg font-semibold shadow-lg shadow-blue-900/20">
                    {{ $home->btn_text }}
                </button>

                <button class="group flex whitespace-nowrap md:w-auto justify-center items-center gap-3 border border-gray-700 hover:border-blue-700 hover:text-blue-400 font-bold px-8 py-3 rounded-lg hover:bg-white/5 hover:-translate-y-1 transition-all duration-300">
                    <div class="border border-gray-600 group-hover:border-blue-400 rounded-full transition-colors duration-300">
                        <svg class="w-6 h-6 text-white group-hover:text-blue-400 transition-colors duration-300"
                             aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                             viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 18V6l8 6-8 6Z"/>
                        </svg>
                    </div>
                    Watch Demo
                </button>
            </div>
        </div>
        <div class="relative w-full">
            <div class="w-full h-auto">
                <img src="{{ asset('images/hero-img.webp') }}" alt="hero-img" class="object-contain w-full h-auto">
            </div>

            <div class="lg:flex absolute -left-10 top-10 hidden lg:block animate-float delay-1 bg-white/5 backdrop-blur-lg border border-white/10 xl:p-4 p-2 rounded-2xl shadow-xl flex items-center xl:gap-4 gap-2">
                <div class="bg-green-200/10 p-3 rounded-lg w-fit">
                    <svg class="w-6 h-6 text-green-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 4.5V19a1 1 0 0 0 1 1h15M7 14l4-4 4 4 5-5m0 0h-3.207M20 9v3.207"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">+127%</h3>
                    <p class="text-xs text-gray-400">Revenue Growth</p>
                </div>
            </div>

            <div class="lg:flex absolute -right-10 bottom-10 hidden lg:block animate-float delay-2 bg-white/5 backdrop-blur-lg border border-white/10 xl:p-4 p-2 rounded-2xl shadow-xl flex items-center xl:gap-4 gap-2">
                <div class="bg-blue-200/10 p-3 rounded-lg w-fit">
                    <svg class="w-6 h-6 text-blue-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                              d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                    </svg>

                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">{{ $home->active_users_count }}</h3>
                    <p class="text-xs text-gray-400">Active Users</p>
                </div>
            </div>

            <div class="lg:flex absolute -left-10 bottom-10 hidden lg:block animate-float delay-2 bg-white/5 backdrop-blur-lg border border-white/10 xl:p-4 p-2 rounded-2xl shadow-xl flex items-center xl:gap-4 gap-2">
                <div class="bg-yellow-200/20 p-3 rounded-lg w-fit">
                    <svg class="w-6 h-6 text-yellow-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>

                </div>
                <div>
                    <h3 class="text-xl font-bold text-white">40%</h3>
                    <p class="text-xs text-gray-400">Time Saved</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
