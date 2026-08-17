<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .filament-primary { background-color: #00E5C0; }
        .filament-primary-text { color: #00E5C0; }
        .filament-primary-bg { background-color: rgba(0, 229, 192, 0.1); }
        .filament-info { background-color: #0066FF; }
        .filament-info-text { color: #0066FF; }
        .filament-info-bg { background-color: rgba(0, 102, 255, 0.1); }
    </style>
</head>
<body class="bg-gray-900 min-h-screen">
    @include('frontend.user.partials.header')
    <div class="flex flex-col flex-1">
        @include('frontend.user.partials.sidebar')
        @yield('content')
    </div>
</body>