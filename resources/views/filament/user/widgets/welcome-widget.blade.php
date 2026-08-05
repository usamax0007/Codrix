<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Welcome to your panel
        </x-slot>

        <x-slot name="description">
            You are signed in as {{ auth()->user()?->name }}. An administrator created this account for you.
        </x-slot>

        <p class="text-sm text-gray-500 dark:text-gray-400">
            Use the account menu to update your profile or sign out. More tools will appear here as they are enabled for your account.
        </p>
    </x-filament::section>
</x-filament-widgets::widget>
