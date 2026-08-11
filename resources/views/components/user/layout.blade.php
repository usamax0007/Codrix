@props([
    'title' => null,
    'wide' => false,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ? $title.' — ' : '' }}XCodrix</title>
    <link rel="icon" href="{{ asset('images/xcodrix-logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'xc-cyan': '#00E5C0',
                        'xc-blue': '#0066FF',
                        'xc-dark': '#050D17',
                        'xc-darker': '#020C19',
                        'xc-card': '#0C1623',
                    },
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/xcodrix.css') }}">
</head>
<body class="min-h-screen bg-xc-darker text-white antialiased">
    <div class="min-h-screen lg:flex">
        {{-- Mobile overlay --}}
        <div
            id="sidebar-overlay"
            class="fixed inset-0 z-40 bg-black/60 opacity-0 pointer-events-none transition-opacity lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Sidebar --}}
        <aside
            id="user-sidebar"
            class="fixed inset-y-0 left-0 z-50 flex w-64 -translate-x-full flex-col border-r border-white/10 bg-xc-dark transition-transform duration-200 lg:static lg:translate-x-0"
        >
            <div class="flex h-16 items-center gap-3 border-b border-white/10 px-5 shrink-0">
                <a href="{{ route('user.dashboard') }}" class="flex items-center gap-3 min-w-0">
                    <img src="{{ asset('images/xcodrix-logo.png') }}" alt="XCodrix" class="h-8 w-auto shrink-0">
                    <span class="font-semibold tracking-tight truncate">Portal</span>
                </a>
                <button
                    type="button"
                    id="sidebar-close"
                    class="ml-auto rounded-lg p-2 text-white/60 hover:bg-white/5 hover:text-white lg:hidden"
                    aria-label="Close menu"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto p-4">
                <p class="mb-2 px-3 text-[11px] font-semibold uppercase tracking-wider text-white/35">Menu</p>

                <x-user.nav-link :href="route('user.dashboard')" :active="request()->routeIs('user.dashboard')">
                    <svg class="h-4 w-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/>
                    </svg>
                    Dashboard
                </x-user.nav-link>

                @can(\App\Support\AppPermission::TASKS_ACCESS)
                    <x-user.nav-link :href="route('user.tasks.index')" :active="request()->routeIs('user.tasks.*')">
                        <svg class="h-4 w-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                        Tasks
                    </x-user.nav-link>
                @endcan

                @can(\App\Support\AppPermission::PROJECTS_ACCESS)
                    <x-user.nav-link :href="route('user.projects.index')" :active="request()->routeIs('user.projects.*')">
                        <svg class="h-4 w-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                        </svg>
                        Projects
                    </x-user.nav-link>
                @endcan

                @can(\App\Support\AppPermission::TASKS_MANAGE_STATUSES)
                    <x-user.nav-link :href="route('user.task-statuses.index')" :active="request()->routeIs('user.task-statuses.*')">
                        <svg class="h-4 w-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Task Statuses
                    </x-user.nav-link>
                @endcan

                @can(\App\Support\AppPermission::ATTENDANCE_ACCESS)
                    <x-user.nav-link :href="route('user.attendance.index')" :active="request()->routeIs('user.attendance.*')">
                        <svg class="h-4 w-4 shrink-0 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Attendance
                    </x-user.nav-link>
                @endcan
            </nav>

            <div class="border-t border-white/10 p-4 shrink-0">
                <div class="mb-3 truncate px-1 text-sm text-white/60">
                    {{ auth()->user()?->name }}
                </div>
                <form method="POST" action="{{ route('user.logout') }}">
                    @csrf
                    <x-user.button type="submit" variant="outline" size="sm" class="w-full">
                        Sign out
                    </x-user.button>
                </form>
            </div>
        </aside>

        {{-- Main --}}
        <div class="flex min-h-screen min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-30 flex h-16 items-center gap-3 border-b border-white/10 bg-xc-darker/90 px-4 backdrop-blur sm:px-6 lg:hidden">
                <button
                    type="button"
                    id="sidebar-open"
                    class="rounded-lg p-2 text-white/70 hover:bg-white/5 hover:text-white"
                    aria-label="Open menu"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <span class="font-semibold tracking-tight">{{ $title ?? 'Portal' }}</span>
            </header>

            <main @class([
                'flex-1 w-full mx-auto px-4 sm:px-6 py-8',
                'max-w-7xl' => $wide,
                'max-w-6xl' => ! $wide,
            ])>
                <x-user.flash />
                {{ $slot }}
            </main>
        </div>
    </div>

    <script>
        (function () {
            const sidebar = document.getElementById('user-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openBtn = document.getElementById('sidebar-open');
            const closeBtn = document.getElementById('sidebar-close');

            if (!sidebar || !overlay) return;

            const open = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('opacity-0', 'pointer-events-none');
            };

            const close = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('opacity-0', 'pointer-events-none');
            };

            openBtn?.addEventListener('click', open);
            closeBtn?.addEventListener('click', close);
            overlay.addEventListener('click', close);
        })();
    </script>
    @stack('scripts')
</body>
</html>
