<aside id="sidebar" class="w-64 bg-gray-800 border-r border-gray-700 fixed h-[91%] lg:block hidden z-50 transition-transform duration-300">
    <div class="flex flex-col h-full justify-between">
        <div class="p-6 flex-1 overflow-y-auto min-h-0 scrollbar-hide">
            <nav class="space-y-2">
                <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.dashboard') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if(auth()->user()->hasPermissionTo('view-projects'))
                <a href="{{ route('user.add-project.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.add-project.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    <span>Add Project</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-tasks'))
                <a href="{{ route('user.task.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.task.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Task</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-task-status'))
                <a href="{{ route('user.task-status.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.task-status.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Task Status</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-orders'))
                <a href="{{ route('user.orders.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.orders.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span>My Orders</span>
                </a>
                @endif
            </nav>
        </div>

        <div class="px-6 py-1 border-t border-gray-700 bg-gray-800">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-gray-700 transition w-full">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </div>
</aside>
