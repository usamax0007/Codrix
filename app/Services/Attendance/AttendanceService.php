<?php

namespace App\Services\Attendance;

use App\Enums\AttendanceHistoryRange;
use App\Exceptions\AttendanceException;
use App\Models\Attendance;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function openSession(User $user): ?Attendance
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereNull('check_out_at')
            ->latest('check_in_at')
            ->first();
    }

    /**
     * @return array<string, array{id: int, check_in_at: string, check_out_at: string|null, is_open: bool}>
     */
    public function statusByDate(User $user, int $days = 14): array
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('work_date', '>=', now()->subDays($days)->toDateString())
            ->get()
            ->mapWithKeys(fn (Attendance $attendance): array => [
                $attendance->work_date->format('Y-m-d') => [
                    'id' => $attendance->id,
                    'check_in_at' => $attendance->check_in_at->format('h:i A'),
                    'check_out_at' => $attendance->check_out_at?->format('h:i A'),
                    'is_open' => $attendance->isOpen(),
                ],
            ])
            ->all();
    }

    public function paginateForUser(
        User $user,
        AttendanceHistoryRange $range = AttendanceHistoryRange::Last7,
        int $perPage = 15,
    ): LengthAwarePaginator {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->when(
                $range->fromDate(),
                fn ($query, string $from) => $query->whereDate('work_date', '>=', $from),
            )
            ->latest('check_in_at')
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

            $workDate = $occurredAt->toDateString();

            $existing = Attendance::query()
                ->where('user_id', $user->id)
                ->whereDate('work_date', $workDate)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                throw AttendanceException::alreadyAttendedToday();
            }

            return Attendance::query()->create([
                'user_id' => $user->id,
                'work_date' => $workDate,
                'check_in_at' => $occurredAt->format('Y-m-d H:i:s'),
            ]);
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

            $attendance->fill([
                'check_out_at' => $occurredAt->format('Y-m-d H:i:s'),
                'worked_minutes' => $minutes,
            ])->save();

            return $attendance->refresh();
        });
    }

    private function lockedOpenSession(User $user): ?Attendance
    {
        return Attendance::query()
            ->where('user_id', $user->id)
            ->whereNull('check_out_at')
            ->lockForUpdate()
            ->latest('check_in_at')
            ->first();
    }
}
