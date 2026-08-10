<x-user.layout title="Dashboard">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">
                Welcome Back, {{ $user->name }}
            </h1>
        </div>

        @if ($canAttendance)
            <x-user.card title="Attendance">
                <x-user.attendance-punch
                    :open-session="$openSession"
                    :status-by-date="$statusByDate"
                />
            </x-user.card>
        @endif
    </div>
</x-user.layout>
