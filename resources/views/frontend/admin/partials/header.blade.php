<header class="bg-gray-800 border-b border-gray-700 h-16 flex items-center justify-between px-6 fixed top-0 left-0 right-0 z-30">
    <div class="flex items-center space-x-4">
        <button onclick="toggleSidebar()" class="lg:hidden text-gray-400 hover:text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        <h1 class="text-xl font-bold text-white">Admin Dashboard</h1>
    </div>
    <div class="flex items-center space-x-4">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-full filament-primary flex items-center justify-center">
                <span class="text-white font-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
            </div>
            <div class="hidden md:block">
                <p class="text-sm font-medium text-white">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-400">
                    @foreach(Auth::user()->roles as $role)
                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</header>
