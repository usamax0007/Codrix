<x-user.guest-layout title="Sign in">
    <x-user.card title="Welcome back" description="Sign in to your XCodrix portal.">
        <form method="POST" action="{{ route('user.login.store') }}" class="space-y-5">
            @csrf

            <x-user.input
                label="Email"
                name="email"
                type="email"
                autocomplete="username"
                required
                autofocus
            />

            <x-user.input
                label="Password"
                name="password"
                type="password"
                autocomplete="current-password"
                required
            />

            <div class="flex items-center justify-between gap-3">
                <x-user.checkbox name="remember" label="Remember me" />
            </div>

            <x-user.button type="submit" class="w-full" size="lg">
                Sign in
            </x-user.button>
        </form>
    </x-user.card>
</x-user.guest-layout>
