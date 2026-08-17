<aside class="w-64 bg-gray-800 border-r border-gray-700 fixed h-full hidden lg:block">
    <div class="p-6">


        <nav class="space-y-2">
            <a href="{{ route('user.dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.dashboard') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('user.add-project.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.add-project.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6C4 4.89543 4.89543 4 6 4H10L12 6H18C19.1046 6 20 6.89543 20 8V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6Z" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>
                    <path d="M12 10V16M9 13H15" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Add Project</span>
            </a>

            <a href="{{ route('user.task.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.task.*') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 3H19C20.1 3 21 3.9 21 5V19C21 20.1 20.1 21 19 21H5C3.9 21 3 20.1 3 19V5C3 3.9 3.9 3 5 3Z" stroke="currentColor" stroke-width="2"/>
                    <path d="M7 8L8.5 9.5L11 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 8H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 13L8.5 14.5L11 12" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13 13H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M7 18H17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Task</span>
            </a>

            <a href="{{ route('user.task-status') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg {{ request()->routeIs('user.task-status') ? 'filament-primary-bg filament-primary-text font-medium' : 'text-gray-300 hover:bg-gray-700 transition' }}">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="2"/>
                    <path d="M12 7V12L15 14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <span>Task status</span>
            </a>
        </nav>
    </div>

    <div class="absolute bottom-0 left-0 right-0 p-6 border-t border-gray-700">
        <form method="POST" action="{{ route('user.logout') }}">
            @csrf
            <button type="submit" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-300 hover:bg-red-900 hover:text-red-400 transition w-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                </svg>
                <span>Logout</span>
            </button>
        </form>
    </div>
</aside>
