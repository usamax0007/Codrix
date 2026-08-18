<?php

namespace App\Enums;

enum AttendanceHistoryRange: string
{
    case Last7 = '7';
    case Last15 = '15';
    case LastMonth = '1m';
    case Last2Months = '2m';
    case All = 'all';

    public function label(): string
    {
        return match ($this) {
            self::Last7 => 'Last 7 days',
            self::Last15 => 'Last 15 days',
            self::LastMonth => 'Last 1 month',
            self::Last2Months => 'Last 2 months',
            self::All => 'All',
        };
    }

    public function fromDate(): ?string
    {
        return match ($this) {
            self::Last7 => now()->subDays(6)->toDateString(),
            self::Last15 => now()->subDays(14)->toDateString(),
            self::LastMonth => now()->subMonth()->toDateString(),
            self::Last2Months => now()->subMonths(2)->toDateString(),
            self::All => null,
        };
    }

    public static function fromRequest(?string $value): self
    {
        return self::tryFrom((string) $value) ?? self::Last7;
    }
}
