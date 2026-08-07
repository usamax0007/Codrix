<?php

namespace App\Models;

use App\Models\Concerns\HasSingletonSettings;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable([
    'working_days',
    'work_from',
    'work_to',
])]
class AttendanceSetting extends Model
{
    use HasSingletonSettings;

    protected function casts(): array
    {
        return [
            'working_days' => 'array',
        ];
    }

    public static function defaults(): array
    {
        return [
            'working_days' => ['1', '2', '3', '4', '5'],
            'work_from' => '09:00',
            'work_to' => '18:00',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function weekdayOptions(): array
    {
        return [
            '1' => 'Monday',
            '2' => 'Tuesday',
            '3' => 'Wednesday',
            '4' => 'Thursday',
            '5' => 'Friday',
            '6' => 'Saturday',
            '0' => 'Sunday',
        ];
    }

    public function isWorkingDay(CarbonInterface $date): bool
    {
        $days = array_map('strval', $this->working_days ?? []);

        return in_array((string) $date->dayOfWeek, $days, true);
    }

    public function workStartOn(CarbonInterface $date): Carbon
    {
        return Carbon::parse($date->toDateString().' '.$this->normalizedTime($this->work_from));
    }

    public function workEndOn(CarbonInterface $date): Carbon
    {
        return Carbon::parse($date->toDateString().' '.$this->normalizedTime($this->work_to));
    }

    public function isLateCheckIn(CarbonInterface $occurredAt): bool
    {
        return $occurredAt->gt($this->workStartOn($occurredAt));
    }

    public function shouldMarkAbsentForDate(CarbonInterface $date, ?CarbonInterface $now = null): bool
    {
        if (! $this->isWorkingDay($date)) {
            return false;
        }

        $now ??= now();
        $day = $date->copy()->startOfDay();

        if ($day->lt($now->copy()->startOfDay())) {
            return true;
        }

        if ($day->isSameDay($now)) {
            return $now->gte($this->workEndOn($date));
        }

        return false;
    }

    protected function normalizedTime(?string $time): string
    {
        if (blank($time)) {
            return '00:00:00';
        }

        return strlen($time) === 5 ? "{$time}:00" : $time;
    }
}
