<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AttendanceStatus: string implements HasColor, HasLabel
{
    case Open = 'open';
    case Present = 'present';
    case Late = 'late';
    case Absent = 'absent';

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => 'Checked in',
            self::Present => 'Present',
            self::Late => 'Late',
            self::Absent => 'Absent',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'warning',
            self::Present => 'success',
            self::Late => 'danger',
            self::Absent => 'gray',
        };
    }
}
