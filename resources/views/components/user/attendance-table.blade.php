@props([
    'attendances',
    'range',
])
@php
    use App\Enums\AttendanceHistoryRange;

    $rangeOptions = collect(AttendanceHistoryRange::cases())
        ->mapWithKeys(fn (AttendanceHistoryRange $item) => [$item->value => $item->label()])
        ->all();
@endphp

<div {{ $attributes->class(['space-y-4']) }}>
    <form method="GET" action="{{ route('user.attendance.index') }}" class="flex justify-end">
        <x-user.select
            label="Show"
            name="range"
            :options="$rangeOptions"
            :value="$range->value"
            onchange="this.form.submit()"
        />
    </form>

    <div class="overflow-x-auto rounded-xl border border-white/10">
        <table class="min-w-full text-sm">
            <thead class="bg-white/5 text-left text-white/50">
                <tr>
                    <th class="px-4 py-3 font-medium">Date</th>
                    <th class="px-4 py-3 font-medium">Check in</th>
                    <th class="px-4 py-3 font-medium">Check out</th>
                    <th class="px-4 py-3 font-medium">Duration</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse ($attendances as $attendance)
                    <tr class="hover:bg-white/[0.03]">
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $attendance->work_date->format('M d, Y') }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $attendance->check_in_at?->format('h:i A') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap">
                            {{ $attendance->check_out_at?->format('h:i A') ?? '—' }}
                        </td>
                        <td class="px-4 py-3 whitespace-nowrap font-medium">
                            {{ $attendance->durationLabel() }}
                        </td>
                        <td class="px-4 py-3">
                            <x-user.status-badge :status="$attendance->status" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-white/50">
                            No attendance records for this period.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($attendances->hasPages())
        <div>
            {{ $attendances->links() }}
        </div>
    @endif
</div>
