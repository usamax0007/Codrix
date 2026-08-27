<aside id="sidebar" class="w-64 bg-gray-800 border-r border-gray-700 fixed h-[91%] lg:block hidden z-50 transition-transform duration-300">
    <div class="flex flex-col h-full justify-between">
        <div class="p-6 flex-1 overflow-y-auto min-h-0 scrollbar-hide">
            <nav class="space-y-2">
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.dashboard') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if(auth()->user()->hasPermissionTo('view-users'))
                <a href="{{ route('super-admin.users.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.users.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Users</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-admins'))
                <a href="{{ route('super-admin.admins.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.admins.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span>Admins</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-projects'))
                <a href="{{ route('super-admin.projects.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.projects.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    <span>Projects</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-tasks'))
                <a href="{{ route('super-admin.tasks.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.tasks.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span>Tasks</span>
                </a>
                @endif

                @if(auth()->user()->hasPermissionTo('view-task-status'))
                <a href="{{ route('super-admin.task-status.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.task-status.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span>Task Status</span>
                </a>
                @endif

                <a href="{{ route('super-admin.roles.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.roles.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    <span>Roles</span>
                </a>

                <a href="{{ route('super-admin.permissions.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('super-admin.permissions.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                    </svg>
                    <span>Permissions</span>
                </a>
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
