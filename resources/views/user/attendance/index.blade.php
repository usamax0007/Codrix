<x-user.layout title="Attendance">
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-semibold tracking-tight">Attendance</h1>
            <p class="mt-2 text-white/60 text-sm sm:text-base">
                One check-in and check-out per day. Duration is calculated automatically.
            </p>
        </div>

        <x-user.card>
            <x-user.attendance-punch
                :open-session="$openSession"
                :status-by-date="$statusByDate"
            />
        </x-user.card>

        <x-user.card title="History" description="Your check-in and check-out records.">
            <x-user.attendance-table :attendances="$attendances" :range="$range" />
        </x-user.card>
    </div>
</x-user.layout>
