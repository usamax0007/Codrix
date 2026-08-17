<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - User Dashboard</title>
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
<body class="bg-gray-900 min-h-screen flex items-center justify-center">
<div class="w-full max-w-md p-8 bg-gray-800 rounded-2xl shadow-xl border border-gray-700">
    <div class="text-center mb-8">
        <div class="w-16 h-16 rounded-xl filament-primary flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white">Welcome Back</h1>
        <p class="text-gray-400 mt-2">Sign in to your account</p>
    </div>

    @if (session('success'))
        <div class="mb-4 p-4 filament-primary-bg border border-filament-primary filament-primary-text rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('user.login.post') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
            <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-[#00E5C0] focus:border-[#00E5C0] text-white placeholder-gray-400 transition duration-200"
                    placeholder="you@example.com"
            >
            @error('email')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
            <div class="relative">
                <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg focus:ring-2 focus:ring-[#00E5C0] focus:border-[#00E5C0] text-white placeholder-gray-400 transition duration-200 pr-12"
                        placeholder="••••••••"
                >
                <button
                        type="button"
                        id="togglePassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-300 focus:outline-none"
                >
                    <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
            @error('password')
            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input type="checkbox" name="remember" class="w-4 h-4 text-[#00E5C0] border-gray-600 rounded focus:ring-[#00E5C0] bg-gray-700">
                <span class="ml-2 text-sm text-gray-400">Remember me</span>
            </label>
        </div>

        <button
                type="submit"
                class="w-full py-3 px-4 filament-primary text-gray-900 font-semibold rounded-lg hover:opacity-90 focus:ring-4 focus:ring-[#00E5C0]/30 transition duration-200"
        >
            Sign In
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-400">
            Don't have an account?
            <a href="#" class="filament-primary-text hover:opacity-80 font-medium">Contact us</a>
        </p>
    </div>
</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeIcon = document.getElementById('eyeIcon');

    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        if (type === 'text') {
            eyeIcon.innerHTML = ` <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>`;
        } else {
            eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                `;
        }
    });
</script>
</body>
</html>
