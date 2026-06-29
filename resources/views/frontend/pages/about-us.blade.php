@extends('frontend.layout.app')
@section('content')

<!-- About Us -->
<section class="py-20 bg-[#0C1828] text-white px-6 pt-32 scroll-reveal">
    <div class="xl:max-w-[82%] max-w-[98%] mx-auto grid lg:grid-cols-2 gap-16 items-center">

        <div class="space-y-6">
            <div class=" rounded-full w-fit px-4 py-2 bg-blue-500/10">
                <span class="text-blue-500 font-bold tracking-wider text-sm">About Us</span>
            </div>
            <h2 class="text-4xl md:text-5xl font-bold leading-tight">We Build Digital Products That Drive Growth</h2>
            <p class="text-gray-400 text-xl">Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium
                doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi
                architecto beatae vitae dicta sunt explicabo.</p>

            <div class="bg-[#101C2D] border border-white/5 rounded-2xl p-8 grid grid-cols-2 gap-8 my-8">
                <div class="text-center">
                    <h3 class="text-4xl font-bold text-blue-500 count-up" data-target="150">0</h3>
                    <p class="text-gray-400 font-bold text-sm">Projects Delivered</p>
                </div>
                <div class="text-center">
                    <h3 class="text-4xl font-bold text-blue-500 count-up" data-target="85">0</h3>
                    <p class="text-gray-400 font-bold text-sm">Happy Clients</p>
                </div>
                <div class="text-center">
                    <h3 class="text-4xl font-bold text-blue-500 count-up" data-target="12">0</h3>
                    <p class="text-gray-400 font-bold text-sm">Years Experience</p>
                </div>
                <div class="text-center">
                    <h3 class="text-4xl font-bold text-blue-500 count-up" data-target="95">0</h3>
                    <p class="text-gray-400 font-bold text-sm">Client Retention</p>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex gap-4">
                    <div class="bg-blue-200/10 p-3 rounded-lg w-fit h-fit">
                        <svg class="w-8 h-8 text-blue-800 mt-1" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m10.051 8.102-3.778.322-1.994 1.994a.94.94 0 0 0 .533 1.6l2.698.316m8.39 1.617-.322 3.78-1.994 1.994a.94.94 0 0 1-1.595-.533l-.4-2.652m8.166-11.174a1.366 1.366 0 0 0-1.12-1.12c-1.616-.279-4.906-.623-6.38.853-1.671 1.672-5.211 8.015-6.31 10.023a.932.932 0 0 0 .162 1.111l.828.835.833.832a.932.932 0 0 0 1.111.163c2.008-1.102 8.35-4.642 10.021-6.312 1.475-1.478 1.133-4.77.855-6.385Zm-2.961 3.722a1.88 1.88 0 1 1-3.76 0 1.88 1.88 0 0 1 3.76 0Z"/>
                        </svg>
                    </div>
                    <div><h4 class="font-bold">Fast Delivery</h4>
                        <p class="text-gray-400 text-sm">Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut
                            odit aut fugit.</p></div>
                </div>
                <div class="flex gap-4">
                    <div class="bg-blue-200/10 p-3 rounded-lg w-fit h-fit">
                        <svg class="w-8 h-8 text-blue-800" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9.5 11.5 11 13l4-3.5M12 20a16.405 16.405 0 0 1-5.092-5.804A16.694 16.694 0 0 1 5 6.666L12 4l7 2.667a16.695 16.695 0 0 1-1.908 7.529A16.406 16.406 0 0 1 12 20Z"/>
                        </svg>
                    </div>
                    <div><h4 class="font-bold">Quality Assured</h4>
                        <p class="text-gray-400 text-sm">Sed quia consequuntur magni dolores eos qui ratione voluptatem
                            sequi nesciunt.</p></div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 justify-start w-full">

                <button class="bg-[#208BF4] whitespace-nowrap w-48 hover:bg-blue-700 hover:-translate-y-1 transition-all duration-300 px-8 py-3 rounded-lg font-semibold shadow-lg shadow-blue-900/20">
                    Start Your Project →
                </button>

                <button class="group flex whitespace-nowrap w-48 justify-center items-center gap-3 border border-gray-700 hover:border-blue-700 hover:text-blue-400 font-bold px-8 py-3 rounded-lg hover:bg-white/5 hover:-translate-y-1 transition-all duration-300">
                    View Portfolio
                </button>
            </div>
        </div>

        <div class="relative h-[500px]">
            <img src="{{ asset('images/about-1.webp') }}"
                 class="absolute lg:-top-40 right-0 w-[80%] md:h-[80%] h-[60%] object-cover rounded-2xl shadow-2xl"
                 alt="Team">


            <img src="{{ asset('images/about-2.webp') }}"
                 class="md:hidden block absolute lg:bottom-[20%] md:bottom-[-10%] bottom-[10%] xl:left-0 lg:left-[-4%] w-[70%] h-[50%] object-cover rounded-2xl shadow-2xl border-4 border-[#020C19]"
                 alt="Work">


            <div class="absolute lg:top-[20%] md:top-[50%] top-[30%] md:right-[0%] right-[15%] bg-[#0A1625] xl:p-6 md:p-3 p-5  rounded-xl border border-white/10 flex items-center gap-3">
                <div class="bg-blue-600 p-2 rounded-lg">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                         viewBox="0 0 24 24">
                        <path d="M11 9a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"/>
                        <path fill-rule="evenodd"
                              d="M9.896 3.051a2.681 2.681 0 0 1 4.208 0c.147.186.38.282.615.255a2.681 2.681 0 0 1 2.976 2.975.681.681 0 0 0 .254.615 2.681 2.681 0 0 1 0 4.208.682.682 0 0 0-.254.615 2.681 2.681 0 0 1-2.976 2.976.681.681 0 0 0-.615.254 2.682 2.682 0 0 1-4.208 0 .681.681 0 0 0-.614-.255 2.681 2.681 0 0 1-2.976-2.975.681.681 0 0 0-.255-.615 2.681 2.681 0 0 1 0-4.208.681.681 0 0 0 .255-.615 2.681 2.681 0 0 1 2.976-2.975.681.681 0 0 0 .614-.255ZM12 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z"
                              clip-rule="evenodd"/>
                        <path d="M5.395 15.055 4.07 19a1 1 0 0 0 1.264 1.267l1.95-.65 1.144 1.707A1 1 0 0 0 10.2 21.1l1.12-3.18a4.641 4.641 0 0 1-2.515-1.208 4.667 4.667 0 0 1-3.411-1.656Zm7.269 2.867 1.12 3.177a1 1 0 0 0 1.773.224l1.144-1.707 1.95.65A1 1 0 0 0 19.915 19l-1.32-3.93a4.667 4.667 0 0 1-3.4 1.642 4.643 4.643 0 0 1-2.53 1.21Z"/>
                    </svg>

                </div>
                <div>
                    <p class="text-sm font-bold">
                        Award Winning
                    </p>
                    <p class="text-xs text-gray-400">
                        Digital Agency 2024
                    </p>
                </div>
            </div>

            <img src="{{ asset('images/about-2.webp') }}"
                 class="md:block hidden absolute lg:bottom-[20%] md:bottom-[-10%] bottom-[20%] xl:left-0 lg:left-[-4%] w-[60%] h-[50%] object-cover rounded-2xl shadow-2xl border-4 border-[#020C19]"
                 alt="Work">
        </div>
    </div>
</section>

@endsection
