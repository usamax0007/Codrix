<?php

namespace App\Services\Attendance;

use App\Enums\AttendanceHistoryRange;
use App\Enums\AttendanceStatus;
use App\Enums\UserRole;
use App\Exceptions\AttendanceException;
use App\Models\Attendance;
use App\Models\AttendanceSetting;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function openSession(User $user): ?Attendance
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->where('status', '!=', AttendanceStatus::Absent)
            ->latest('check_in_at')
            ->first();
    }

    /**
     * @return array<string, array{id: int, check_in_at: string|null, check_out_at: string|null, is_open: bool, status: string}>
     */
    public function statusByDate(User $user, int $days = 14): array
    {
        $this->ensureAbsentsForUser($user, now()->subDays($days)->startOfDay(), now());

        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('work_date', '>=', now()->subDays($days)->toDateString())
            ->get()
            ->mapWithKeys(fn (Attendance $attendance): array => [
                $attendance->work_date->format('Y-m-d') => [
                    'id' => $attendance->id,
                    'check_in_at' => $attendance->check_in_at?->format('h:i A'),
                    'check_out_at' => $attendance->check_out_at?->format('h:i A'),
                    'is_open' => $attendance->isOpen(),
                    'status' => $attendance->status->value,
                ],
            ])
            ->all();
    }

    public function paginateForUser(
        User $user,
        AttendanceHistoryRange $range = AttendanceHistoryRange::Last7,
        int $perPage = 15,
    ): LengthAwarePaginator {
        $from = $range->fromDate()
            ? Carbon::parse($range->fromDate())->startOfDay()
            : now()->subYear()->startOfDay();

        $this->ensureAbsentsForUser($user, $from, now());

        return Attendance::query()
            ->where('user_id', $user->id)
            ->when(
                $range->fromDate(),
                fn ($query, string $date) => $query->whereDate('work_date', '>=', $date),
            )
            ->latest('work_date')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * @throws AttendanceException
     */
    public function checkIn(User $user, CarbonInterface $occurredAt): Attendance
    {
        return DB::transaction(function () use ($user, $occurredAt): Attendance {
            if ($this->lockedOpenSession($user)) {
                throw AttendanceException::alreadyCheckedIn();
            }

            $settings = AttendanceSetting::current();
            $workDate = $occurredAt->toDateString();
            $status = $settings->isLateCheckIn($occurredAt)
                ? AttendanceStatus::Late
                : AttendanceStatus::Open;

            $existing = Attendance::query()
                ->where('user_id', $user->id)
                ->whereDate('work_date', $workDate)
                ->lockForUpdate()
                ->first();

            if ($existing && ! $existing->isAbsent()) {
                throw AttendanceException::alreadyAttendedToday();
            }

            $payload = [
                'user_id' => $user->id,
                'work_date' => $workDate,
                'check_in_at' => $occurredAt->format('Y-m-d H:i:s'),
                'check_out_at' => null,
                'worked_minutes' => null,
                'status' => $status,
            ];

            if ($existing?->isAbsent()) {
                $existing->fill($payload)->save();

                return $existing->refresh();
            }

            return Attendance::query()->create($payload);
        });
    }

    /**
     * @throws AttendanceException
     */
    public function checkOut(User $user, CarbonInterface $occurredAt): Attendance
    {
        return DB::transaction(function () use ($user, $occurredAt): Attendance {
            $attendance = $this->lockedOpenSession($user);

            if (! $attendance) {
                throw AttendanceException::notCheckedIn();
            }

            if ($occurredAt->lt($attendance->check_in_at)) {
                throw AttendanceException::checkoutBeforeCheckIn();
            }

            $minutes = max(0, (int) $attendance->check_in_at->diffInMinutes($occurredAt));

            // Keep Late if they checked in late; otherwise mark Present.
            $status = $attendance->status === AttendanceStatus::Late
                ? AttendanceStatus::Late
                : AttendanceStatus::Present;

            $attendance->fill([
                'check_out_at' => $occurredAt->format('Y-m-d H:i:s'),
                'worked_minutes' => $minutes,
                'status' => $status,
            ])->save();

            return $attendance->refresh();
        });
    }

    public function ensureAbsentsForUser(User $user, CarbonInterface $from, CarbonInterface $to): void
    {
        $settings = AttendanceSetting::current();
        $cursor = $from->copy()->startOfDay();
        $end = $to->copy()->startOfDay();

        while ($cursor->lte($end)) {
            if ($settings->shouldMarkAbsentForDate($cursor, $to)) {
                $this->markAbsentIfMissing($user, $cursor->toDateString());
            }

            $cursor->addDay();
        }
    }

    public function markAbsentsForAllStaff(?CarbonInterface $now = null): int
    {
        $now ??= now();
        $settings = AttendanceSetting::current();
        $from = $now->copy()->subDays(60)->startOfDay();
        $created = 0;

        User::query()
            ->where('role', UserRole::User)
            ->each(function (User $user) use ($settings, $from, $now, &$created): void {
                $cursor = $from->copy();

                while ($cursor->lte($now->copy()->startOfDay())) {
                    if ($settings->shouldMarkAbsentForDate($cursor, $now)) {
                        if ($this->markAbsentIfMissing($user, $cursor->toDateString())) {
                            $created++;
                        }
                    }

                    $cursor->addDay();
                }
            });

        return $created;
    }

    public function markAbsentIfMissing(User $user, string $workDate): bool
    {
        $exists = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('work_date', $workDate)
            ->exists();

        if ($exists) {
            return false;
        }

        Attendance::query()->create([
            'user_id' => $user->id,
            'work_date' => $workDate,
            'check_in_at' => null,
            'check_out_at' => null,
            'worked_minutes' => null,
            'status' => AttendanceStatus::Absent,
        ]);

        return true;
    }

    private function lockedOpenSession(User $user): ?Attendance
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereNotNull('check_in_at')
            ->whereNull('check_out_at')
            ->where('status', '!=', AttendanceStatus::Absent)
            ->lockForUpdate()
            ->latest('check_in_at')
            ->first();
    }
}
