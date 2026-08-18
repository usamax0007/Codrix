<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasLabel
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case User = 'user';

    public function getLabel(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::User => 'User',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SuperAdmin => 'warning',
            self::Admin => 'danger',
            self::User => 'info',
        };
    }

    /**
     * Roles SuperAdmin can assign when creating Admin / User accounts.
     *
     * @return array<string, string>
     */
    public static function assignableOptions(): array
    {
        return [
            self::Admin->value => self::Admin->getLabel(),
            self::User->value => self::User->getLabel(),
        ];
    }
}
