<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-900 text-white font-sans antialiased no-scrollbar">

<div class="flex h-screen bg-gray-900 overflow-hidden">

    <!-- Mobile Backdrop / Overlay -->
    <div
            id="sidebarBackdrop"
            class="fixed inset-0 z-40 bg-black/60 hidden opacity-0 transition-opacity duration-300 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside
            id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-64 bg-gray-800 border-r border-gray-700 flex flex-col -translate-x-full transition-transform duration-300 ease-in-out lg:static lg:translate-x-0">

        <!-- Logo Area -->
        <div class="p-5 text-xl font-bold text-emerald-400 border-b border-gray-700 flex items-center justify-between h-16 shrink-0">
            <span>XCodrix</span>

            <!-- Mobile Close Button -->
            <button id="closeSidebar"
                    class="lg:hidden text-gray-400 hover:text-white p-1 rounded-md focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <!-- Dashboard -->
            <a href="/user/dashboard"
               class="flex items-center gap-3 px-4 py-2.5 rounded transition {{ request()->is('user/dashboard*') ? 'bg-emerald-500/10 text-emerald-400 font-semibold border-l-4 border-emerald-400' : 'text-gray-300 hover:bg-gray-700' }}">
                <span>Dashboard</span>
            </a>

            <!-- Projects -->
            <a href="{{ route('projects.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded transition {{ request()->routeIs('projects.*') || request()->is('user/projects*') ? 'bg-emerald-500/10 text-emerald-400 font-semibold border-l-4 border-emerald-400' : 'text-gray-300 hover:bg-gray-700' }}">
                <span>Projects</span>
            </a>

            <!-- Tasks -->
            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded transition {{ request()->routeIs('tasks.*') || request()->is('user/tasks*') ? 'bg-emerald-500/10 text-emerald-400 font-semibold border-l-4 border-emerald-400' : 'text-gray-300 hover:bg-gray-700' }}">
                <span>Tasks</span>
            </a>

            <!-- Task Status -->
            <a href="{{ route('task-statuses.index') }}"
               class="flex items-center gap-3 px-4 py-2.5 rounded transition {{ request()->routeIs('task-statuses.*') || request()->is('user/task-status*') ? 'bg-emerald-500/10 text-emerald-400 font-semibold border-l-4 border-emerald-400' : 'text-gray-300 hover:bg-gray-700' }}">
                <span>Task Status</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">
        <!-- Header Area -->
        <header class="bg-gray-800 border-b border-gray-700 p-4 flex justify-between items-center px-4 sm:px-6 h-16 shrink-0">
            <!-- Hamburger Button (Mobile) -->
            <button id="openSidebar"
                    class="lg:hidden text-gray-400 hover:text-emerald-400 focus:outline-none p-1.5 rounded-lg hover:bg-gray-700 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 18h16"/>
                </svg>
            </button>

            <!-- Right User Info & Logout -->
            <div class="flex items-center gap-4 ml-auto">
        <span class="text-sm text-gray-300">
            Welcome, <strong class="text-emerald-400">{{ Auth::user()->name ?? 'User' }}</strong>
        </span>

                <!-- Logout Form -->
                <form action="/user/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="text-xs bg-red-500/10 border border-red-500/30 text-red-400 hover:bg-red-500 hover:text-white px-3 py-1.5 rounded-lg transition duration-200">
                        Logout
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-4 sm:p-6 no-scrollbar">
            @yield('content')
        </main>
    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const sidebar = document.getElementById('sidebar');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const backdrop = document.getElementById('sidebarBackdrop');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            sidebar.classList.add('translate-x-0');

            backdrop.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                backdrop.classList.add('opacity-100');
            }, 10);
        }

        function closeSidebar() {
            sidebar.classList.remove('translate-x-0');
            sidebar.classList.add('-translate-x-full');

            backdrop.classList.remove('opacity-100');
            backdrop.classList.add('opacity-0');
            setTimeout(() => {
                backdrop.classList.add('hidden');
            }, 300);
        }

        if (openBtn) openBtn.addEventListener('click', openSidebar);
        if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);
    });
</script>

</body>
</html>