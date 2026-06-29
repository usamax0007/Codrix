@extends('frontend.layout.app')
@section('content')

    <!-- Testimonials -->
    <section class="xl:max-w-[82%] max-w-[98%] mx-auto py-20 pt-32 px-7 scroll-reveal">

        <!-- Heading -->
        <div class="text-center mb-16">
        <span class="inline-block px-4 py-1.5 rounded-full bg-blue-800/30 text-blue-500 text-sm font-bold uppercase tracking-widest mb-4">
            Testimonials
        </span>
            <h2 class="text-4xl md:text-5xl font-bold">Check Our
                <span class="text-blue-500">Testimonials</span>
            </h2>
        </div>
        <div id="testimonial-wrapper"
             class="w-full bg-[#101C2D] rounded-3xl
           border border-white/5
           p-8 md:p-12
           flex flex-col lg:flex-row
           items-center
           justify-between
           gap-10
           transition-all duration-500 ease-in-out">

            <!-- Left -->
            <div class="flex-1 min-w-0">

                <h3 id="testimonial-title"
                    class="text-2xl md:text-3xl font-bold mb-6">
                </h3>

                <p id="testimonial-text"
                   class="text-gray-300 text-lg leading-8 italic mb-8">
                </p>

                <div class="flex items-center gap-4">
                    <img id="testimonial-img-small"
                         src=""
                         class="w-14 h-14 rounded-full object-cover">

                    <div>
                        <h4 id="testimonial-name" class="font-bold text-lg"></h4>
                        <p id="testimonial-role" class="text-gray-400"></p>
                    </div>
                </div>

            </div>

            <!-- Right -->
            <div class="hidden lg:flex justify-center w-[380px] flex-shrink-0">

                <img id="testimonial-img-large"
                     src=""
                     class="w-full h-[420px] object-cover rounded-2xl">

            </div>

        </div>
        <div class="flex justify-center gap-5 mt-10">
            <button onclick="prevSlide()"
                    class="w-12 h-12 rounded-full border border-gray-700 hover:bg-blue-500 hover:border-blue-500 transition">
                <svg class="w-6 h-6 mx-auto"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="nextSlide()"
                    class="w-12 h-12 rounded-full border border-gray-700 hover:bg-blue-500 hover:border-blue-500 transition">
                <svg class="w-6 h-6 mx-auto"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </section>

@endsection
