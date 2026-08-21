<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 min-h-screen">
    @include('frontend.user.partials.header')
    <div class="flex flex-col flex-1">
        @include('frontend.user.partials.sidebar')
        @yield('content')
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script src="{{ asset('js/user.js') }}">

    </script>
</body>