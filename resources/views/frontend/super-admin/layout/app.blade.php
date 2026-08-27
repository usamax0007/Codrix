<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .filament-primary { background-color: #0066FF; }
        .filament-primary-text { color: #0066FF; }
        .filament-primary-bg { background-color: rgba(0, 102, 255, 0.1); }
        .filament-info { background-color: #00E5C0; }
        .filament-info-text { color: #00E5C0; }
        .filament-info-bg { background-color: rgba(0, 229, 192, 0.1); }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    @include('frontend.super-admin.partials.header')
    <div class="flex flex-col flex-1">
        @include('frontend.super-admin.partials.sidebar')
        @yield('content')
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script src="{{ asset('js/user.js') }}"></script>
</body>
</html>
