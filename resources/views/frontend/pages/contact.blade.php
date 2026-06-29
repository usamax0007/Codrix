@extends('frontend.layout.app')
@section('content')

    <!-- Contact -->
    <section class="bg-[#020D1A] py-20 px-6 text-white pt-32 scroll-reveal">
        <div class="xl:max-w-[82%] max-w-[98%] mx-auto">
            <!-- Header -->
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-2 rounded-full bg-blue-900/30 text-blue-500 text-xs font-bold uppercase tracking-widest mb-4">Contact</span>
                <h2 class="text-4xl font-bold">Need Help? <span class="text-blue-500">Contact Us</span></h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">

                <!-- Left Side -->
                <div class="py-12">
                    <div class="inline-flex items-center gap-2 mb-4 px-4 py-2 rounded-full bg-blue-900/30">
                        <svg class="w-4 h-4 text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                             width="24"
                             height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m10.051 8.102-3.778.322-1.994 1.994a.94.94 0 0 0 .533 1.6l2.698.316m8.39 1.617-.322 3.78-1.994 1.994a.94.94 0 0 1-1.595-.533l-.4-2.652m8.166-11.174a1.366 1.366 0 0 0-1.12-1.12c-1.616-.279-4.906-.623-6.38.853-1.671 1.672-5.211 8.015-6.31 10.023a.932.932 0 0 0 .162 1.111l.828.835.833.832a.932.932 0 0 0 1.111.163c2.008-1.102 8.35-4.642 10.021-6.312 1.475-1.478 1.133-4.77.855-6.385Zm-2.961 3.722a1.88 1.88 0 1 1-3.76 0 1.88 1.88 0 0 1 3.76 0Z"/>
                        </svg>

                        <span class="text-blue-500 text-xs font-bold whitespace-nowrap">
                        Let's Build Something Amazing
                    </span>
                    </div>

                    <h2 class="text-[40px] font-bold mb-6 leading-tight">Ready to Transform Your Digital Presence?</h2>
                    <p class="text-gray-300 mb-8">Sed ut perspiciatis unde omnis iste natus error sit
                        voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo
                        inventore
                        veritatis et quasi architecto beatae vitae dicta sunt explicabo.</p>

                    <!-- Contact Cards -->
                    <div class="space-y-4">
                        <div
                            class="bg-[#141E2B] p-5 rounded-xl border border-gray-800 flex items-center gap-4 hover:border-blue-800 hover:-translate-y-2 transition-all">
                            <div class="bg-blue-600/10 p-3 rounded-lg text-blue-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Email</h4>
                                <p class="text-md font-bold text-gray-200">{{ $settings['contact_email'] ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-400 mt-1">We reply within 4 hours</p>
                            </div>
                        </div>

                        <div
                            class="bg-[#141E2B] p-5 rounded-xl border border-gray-800 flex items-center gap-4 hover:border-blue-800 hover:-translate-y-2 transition-all">
                            <div class="bg-blue-600/10 p-3 rounded-lg text-blue-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M6.62 10.79c1.44 2.83 3.76 5.15 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Phone</h4>
                                <p class="text-md font-bold text-gray-200">{{ $settings['contact_phone'] ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-400 mt-1">Mon-Fri, 9AM-6PM EST</p>
                            </div>
                        </div>

                        <div
                            class="bg-[#141E2B] p-5 rounded-xl border border-gray-800 flex items-center gap-4 hover:border-blue-800 hover:-translate-y-2 transition-all">
                            <div class="bg-blue-600/10 p-3 rounded-lg text-blue-500">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Office</h4>
                                <p class="text-md font-bold text-gray-200">{{ $settings['contact_address'] ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-400 mt-1">Visit us anytime</p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Stats -->
                    <div
                        class="flex flex-col lg:flex-row items-center justify-between bg-[#141E2B] border border-gray-800 rounded-xl p-6 mt-10 gap-6 lg:gap-0">

                        <div class="text-center flex-1 w-full border-b border-gray-700 pb-6 lg:border-b-0 lg:pb-0">
                            <h3 class="text-3xl font-bold text-[#1D7AD8]">{{ $settings['happy_clients'] ?? '0' }}</h3>
                            <p class="text-sm text-gray-300 font-medium mt-1">Happy Clients</p>
                        </div>

                        <div class="hidden lg:block h-10 w-[1px] bg-gray-700"></div>

                        <div class="text-center flex-1 w-full border-b border-gray-700 pb-6 lg:border-b-0 lg:pb-0">
                            <h3 class="text-3xl font-bold text-[#1D7AD8]">{{ $settings['client_rating'] ?? '0' }}</h3>
                            <p class="text-sm text-gray-300 font-medium mt-1">Client Rating</p>
                        </div>

                        <div class="hidden lg:block h-10 w-[1px] bg-gray-700"></div>

                        <div class="text-center flex-1 w-full">
                            <h3 class="text-3xl font-bold text-[#1D7AD8]">{{ $settings['avg_response'] ?? '0' }}</h3>
                            <p class="text-sm text-gray-300 font-medium mt-1">Avg Response</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side Form -->
                <div class="bg-[#141E2B] p-8 rounded-2xl border border-gray-800 py-12 xl:mt-32 2xl:mt-16">
                    <h3 class="text-3xl font-bold mb-1">Start Your Project</h3>
                    <p class="text-gray-400 text-md font-semibold mb-6">Tell us about your project and we'll get back to
                        you
                        with a
                        tailored solution.
                    </p>

                    @if(session('success'))
                        <div class="bg-[#3B82F6] text-white p-4 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ url('/contact') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="text-sm font-bold text-gray-300">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="w-full bg-[#141E2B] border border-gray-700 rounded-lg p-3 mt-1 focus:border-blue-500 outline-none">
                            @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-bold text-gray-300">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="w-full bg-[#141E2B] border border-gray-700 rounded-lg p-3 mt-1 focus:border-blue-500 outline-none">
                            @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-bold text-gray-300">Subject</label>
                            <input type="text" name="subject" value="{{ old('subject') }}"
                                   class="w-full bg-[#141E2B] border border-gray-700 rounded-lg p-3 mt-1 focus:border-blue-500 outline-none">
                            @error('subject')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-sm font-bold text-gray-300">Message</label>
                            <textarea name="message" rows="4"
                                      class="w-full bg-[#141E2B] border @error('message') border-red-500 @else border-gray-700 @enderror rounded-lg p-3 mt-1 focus:border-blue-500 outline-none">{{ old('message') }}</textarea>

                            @error('message')
                            <p class="text-red-500 text-xs mt-1 font-semibold">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit"
                                class="w-full bg-[#208BF4] hover:bg-[#1D7AD8] transition py-3 rounded-lg font-bold flex justify-center items-center gap-2">
                            Send Message →
                        </button>
                    </form>
                    <p class="text-sm text-gray-300 text-center mt-4 flex justify-center items-center gap-1">
                        <svg class="w-6 h-6 text-[#1D7AD8]" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                             viewBox="0 0 24 24">
                            <path fill-rule="evenodd"
                                  d="M11.644 3.066a1 1 0 0 1 .712 0l7 2.666A1 1 0 0 1 20 6.68a17.694 17.694 0 0 1-2.023 7.98 17.406 17.406 0 0 1-5.402 6.158 1 1 0 0 1-1.15 0 17.405 17.405 0 0 1-5.403-6.157A17.695 17.695 0 0 1 4 6.68a1 1 0 0 1 .644-.949l7-2.666Zm4.014 7.187a1 1 0 0 0-1.316-1.506l-3.296 2.884-.839-.838a1 1 0 0 0-1.414 1.414l1.5 1.5a1 1 0 0 0 1.366.046l4-3.5Z"
                                  clip-rule="evenodd"/>
                        </svg>

                        Your Information is secure and will never be shared
                    </p>
                </div>

            </div>
        </div>
    </section>

@endsection
