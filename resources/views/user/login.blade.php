<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login - XCodrix</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-900 text-white font-sans antialiased min-h-screen flex items-center justify-center p-4">

<div class="w-full max-w-md bg-gray-800 border border-gray-700 rounded-xl shadow-2xl p-8">

    <!-- Header / Logo -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-emerald-400 tracking-wider">XCodrix</h1>
        <p class="text-gray-400 text-sm mt-2">Welcome back! Please enter your details.</p>
    </div>

    <!-- Form Tag Update -->
    <form action="/user/login" method="POST" class="space-y-5">
        @csrf

        <!-- Error Message Alert -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/50 text-red-400 text-sm p-3 rounded-lg">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Email Field -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
                   placeholder="user@example.com">
        </div>

        <!-- Password Field -->
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-medium text-gray-300">Password</label>
                <a href="#" class="text-xs text-emerald-400 hover:underline">Forgot password?</a>
            </div>
            <input type="password" id="password" name="password" required
                   class="w-full px-4 py-2.5 rounded-lg bg-gray-900 border border-gray-700 text-white placeholder-gray-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
                   placeholder="••••••••">
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <input type="checkbox" id="remember" name="remember"
                   class="w-4 h-4 rounded bg-gray-900 border-gray-700 text-emerald-500 focus:ring-emerald-500 focus:ring-offset-gray-800">
            <label for="remember" class="ml-2 text-sm text-gray-400">Remember me</label>
        </div>

        <!-- Submit Button -->
        <button type="submit"
                class="w-full py-3 px-4 bg-emerald-500 hover:bg-emerald-600 text-gray-900 font-semibold rounded-lg shadow-md transition duration-200">
            Sign In
        </button>
    </form>

</div>

</body>
</html>