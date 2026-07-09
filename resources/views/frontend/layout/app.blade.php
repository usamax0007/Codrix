<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @include('frontend.partials.seo')
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
    @stack('head')
</head>
<body class="bg-xc-darker text-white antialiased">
<a href="#main-content" class="skip-link">Skip to main content</a>

@include('frontend.partials.header')

<main id="main-content">
    @yield('content')
</main>

@include('frontend.partials.footer')

<button id="backToTop" type="button" aria-label="Back to top"
        class="fixed bottom-6 right-6 p-3 rounded-lg bg-gradient-to-r from-xc-cyan to-xc-blue text-white opacity-0 invisible transition-all duration-300 z-50 shadow-lg">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
    </svg>
</button>

@stack('scripts')
<script src="{{ asset('js/xcodrix.js') }}" defer></script>
</body>
</html>
