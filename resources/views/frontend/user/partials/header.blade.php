<!-- Header -->
<header class="bg-gray-800 border-b border-gray-700 sticky top-0 z-10">
    <div class="px-6 py-4 flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button class="lg:hidden p-2 rounded-lg hover:bg-gray-700" onclick="toggleSidebar()">
                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-lg filament-primary flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white">Codrix</span>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            @auth
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full filament-primary flex items-center justify-center text-white font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="hidden sm:block">
                    <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
                </div>
            </div>
            @endauth
        </div>
    </div>
</header>