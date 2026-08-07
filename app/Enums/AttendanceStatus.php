<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AttendanceStatus: string implements HasColor, HasLabel
{
    case Open = 'open';
    case Completed = 'completed';

    public function getLabel(): string
    {
        return match ($this) {
            self::Open => 'Checked in',
            self::Completed => 'Completed',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Open => 'warning',
            self::Completed => 'success',
        };
    }
}
