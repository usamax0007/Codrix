@props([
    'title' => null,
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
    <div class="min-h-screen flex flex-col items-center justify-center px-4 py-10">
        <a href="{{ url('/') }}" class="mb-8 flex items-center gap-3">
            <img src="{{ asset('images/xcodrix-logo.png') }}" alt="XCodrix" class="h-10 w-auto">
        </a>

        <div class="w-full max-w-md">
            <x-user.flash />
            {{ $slot }}
        </div>
    </div>
</body>
</html>
