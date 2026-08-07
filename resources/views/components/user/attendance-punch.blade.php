@props([
    'openSession' => null,
    'statusByDate' => [],
    'showDate' => true,
    'workingDays' => null,
])
@php
    $workingDays = array_map(
        'strval',
        $workingDays ?? \App\Models\AttendanceSetting::current()->working_days ?? [],
    );

    $openPayload = $openSession ? [
        'id' => $openSession->id,
        'work_date' => $openSession->work_date->format('Y-m-d'),
        'check_in_at' => $openSession->check_in_at?->format('h:i A'),
    ] : null;
@endphp

<div
    {{ $attributes->class(['flex flex-col sm:flex-row sm:items-center justify-between gap-5']) }}
    data-attendance-punch
    data-status='@json($statusByDate)'
    data-open='@json($openPayload)'
    data-working-days='@json($workingDays)'
>
    @if ($showDate)
        <div class="rounded-xl border border-white/10 bg-xc-darker/60 px-4 py-3 sm:min-w-[14rem]">
            <p class="text-xs uppercase tracking-wide text-white/45">Today</p>
            <p data-device-day class="mt-0.5 text-sm font-medium text-xc-cyan">—</p>
            <p data-device-date class="text-base font-semibold tracking-tight">—</p>
        </div>
    @endif

    <div class="flex flex-col items-stretch sm:items-end gap-2">
        <form
            method="POST"
            action="{{ route('user.attendance.check-in') }}"
            data-device-time-form
            data-punch-check-in
            class="hidden"
        >
            @csrf
            <input type="hidden" name="occurred_at" value="">
            <x-user.button type="submit" size="lg">
                Check in
            </x-user.button>
        </form>

        <form
            method="POST"
            action="{{ route('user.attendance.check-out') }}"
            data-device-time-form
            data-punch-check-out
            class="hidden"
        >
            @csrf
            <input type="hidden" name="occurred_at" value="">
            <x-user.button type="submit" variant="danger" size="lg">
                Check out
            </x-user.button>
        </form>

        <p data-punch-done class="hidden text-sm text-white/60 sm:text-right">
            Attendance completed for today.
        </p>

        <p data-punch-offday class="hidden text-sm text-white/60 sm:text-right">
            Non-working day — check-in is not available.
        </p>
    </div>
</div>

<script>
    (function () {
        const root = document.currentScript.previousElementSibling?.matches?.('[data-attendance-punch]')
            ? document.currentScript.previousElementSibling
            : document.querySelector('[data-attendance-punch]');

        if (!root || root.dataset.bound === '1') {
            return;
        }
        root.dataset.bound = '1';

        const pad = (n) => String(n).padStart(2, '0');
        const now = new Date();
        const today = [
            now.getFullYear(),
            pad(now.getMonth() + 1),
            pad(now.getDate()),
        ].join('-');

        const dayEl = root.querySelector('[data-device-day]');
        const dateEl = root.querySelector('[data-device-date]');

        if (dayEl) {
            dayEl.textContent = now.toLocaleDateString(undefined, { weekday: 'long' });
        }

        if (dateEl) {
            dateEl.textContent = now.toLocaleDateString(undefined, {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
            });
        }

        const statusByDate = JSON.parse(root.dataset.status || '{}');
        const openSession = JSON.parse(root.dataset.open || 'null');
        const workingDays = JSON.parse(root.dataset.workingDays || '[]').map(String);
        const todayRecord = statusByDate[today] || null;
        const isWorkingDay = workingDays.includes(String(now.getDay()));

        const checkIn = root.querySelector('[data-punch-check-in]');
        const checkOut = root.querySelector('[data-punch-check-out]');
        const done = root.querySelector('[data-punch-done]');
        const offDay = root.querySelector('[data-punch-offday]');

        const show = (el) => el?.classList.remove('hidden');
        const hide = (el) => el?.classList.add('hidden');

        hide(checkIn);
        hide(checkOut);
        hide(done);
        hide(offDay);

        if (openSession || todayRecord?.is_open) {
            show(checkOut);
        } else if (todayRecord) {
            show(done);
            if (done && todayRecord.status === 'absent') {
                done.textContent = 'Marked absent for today.';
            } else if (done && todayRecord.status === 'late') {
                done.textContent = 'Attendance completed (late).';
            }
        } else if (!isWorkingDay) {
            show(offDay);
        } else {
            show(checkIn);
        }

        function deviceDateTime() {
            const d = new Date();

            return [
                d.getFullYear(),
                pad(d.getMonth() + 1),
                pad(d.getDate()),
            ].join('-') + ' ' + [
                pad(d.getHours()),
                pad(d.getMinutes()),
                pad(d.getSeconds()),
            ].join(':');
        }

        root.querySelectorAll('[data-device-time-form]').forEach((form) => {
            form.addEventListener('submit', () => {
                const input = form.querySelector('input[name="occurred_at"]');
                if (input) {
                    input.value = deviceDateTime();
                }
            });
        });
    })();
</script>
