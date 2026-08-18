<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .filament-primary { background-color: #00E5C0; }
        .filament-primary-text { color: #00E5C0; }
        .filament-primary-bg { background-color: rgba(0, 229, 192, 0.1); }
        .filament-info { background-color: #0066FF; }
        .filament-info-text { color: #0066FF; }
        .filament-info-bg { background-color: rgba(0, 102, 255, 0.1); }
        
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    @include('frontend.user.partials.header')
    <div class="flex flex-col flex-1">
        @include('frontend.user.partials.sidebar')
        @yield('content')
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (sidebar.classList.contains('hidden')) {
                sidebar.classList.remove('hidden');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('hidden');
                overlay.classList.add('hidden');
            }
        }
    </script>
</body>