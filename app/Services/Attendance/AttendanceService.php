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

    public function firstCheckInDate(User $user): ?Carbon
    {
        $date = Attendance::query()
            ->where('user_id', $user->id)
            ->whereNotNull('check_in_at')
            ->min('work_date');

        return $date ? Carbon::parse($date)->startOfDay() : null;
    }

    /**
     * @return array<string, array{id: int, check_in_at: string|null, check_out_at: string|null, is_open: bool, status: string}>
     */
    public function statusByDate(User $user, int $days = 14): array
    {
        $requestedFrom = now()->subDays($days)->startOfDay();
        $from = $this->timelineStart($user, $requestedFrom);

        if ($from) {
            $this->ensureAbsentsForUser($user, $from, now());
        }

        $query = Attendance::query()->where('user_id', $user->id);

        if ($from) {
            $query->whereDate('work_date', '>=', $from->toDateString());
        } else {
            $query->whereRaw('0 = 1');
        }

        return $query
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
        $this->pruneAbsentsBeforeFirstCheckIn($user);

        $firstCheckIn = $this->firstCheckInDate($user);

        if (! $firstCheckIn) {
            return Attendance::query()
                ->whereRaw('0 = 1')
                ->paginate($perPage)
                ->withQueryString();
        }

        $requestedFrom = $range->fromDate()
            ? Carbon::parse($range->fromDate())->startOfDay()
            : $firstCheckIn->copy();

        $from = $requestedFrom->lt($firstCheckIn) ? $firstCheckIn->copy() : $requestedFrom;

        $this->ensureAbsentsForUser($user, $from, now());

        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('work_date', '>=', $from->toDateString())
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

            if (! $settings->isWorkingDay($occurredAt)) {
                throw AttendanceException::nonWorkingDay();
            }

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
        $start = $this->timelineStart($user, $from);

        if (! $start) {
            return;
        }

        $settings = AttendanceSetting::current();
        $cursor = $start->copy()->startOfDay();
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
        $created = 0;

        User::query()
            ->where('role', UserRole::User)
            ->each(function (User $user) use ($now, &$created): void {
                $this->pruneAbsentsBeforeFirstCheckIn($user);

                $firstCheckIn = $this->firstCheckInDate($user);

                if (! $firstCheckIn) {
                    return;
                }

                $before = Attendance::query()
                    ->where('user_id', $user->id)
                    ->where('status', AttendanceStatus::Absent)
                    ->count();

                $this->ensureAbsentsForUser($user, $firstCheckIn, $now);

                $after = Attendance::query()
                    ->where('user_id', $user->id)
                    ->where('status', AttendanceStatus::Absent)
                    ->count();

                $created += max(0, $after - $before);
            });

        return $created;
    }

    public function pruneAbsentsBeforeFirstCheckIn(User $user): void
    {
        $firstCheckIn = $this->firstCheckInDate($user);

        $query = Attendance::query()
            ->where('user_id', $user->id)
            ->where('status', AttendanceStatus::Absent);

        if (! $firstCheckIn) {
            $query->delete();

            return;
        }

        $query->whereDate('work_date', '<', $firstCheckIn->toDateString())->delete();
    }

    public function markAbsentIfMissing(User $user, string $workDate): bool
    {
        $firstCheckIn = $this->firstCheckInDate($user);

        if (! $firstCheckIn || Carbon::parse($workDate)->lt($firstCheckIn)) {
            return false;
        }

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

    private function timelineStart(User $user, CarbonInterface $requestedFrom): ?Carbon
    {
        $firstCheckIn = $this->firstCheckInDate($user);

        if (! $firstCheckIn) {
            return null;
        }

        $requested = $requestedFrom->copy()->startOfDay();

        return $requested->lt($firstCheckIn) ? $firstCheckIn->copy() : $requested;
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
